<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User as UserOrm;
use Orm\UserRole;
use Orm\Role;
use Illuminate\Database\Capsule\Manager as DB;
use Grahes\Validator\ValidatorFactory;

class User extends CI_Controller
{
    public function index()
    {
        redirect('login');
    }

    public function registration()
    {
        $registration_sukses = '';
        $list_errors = [];

        $user_input = [
            'fullname' => '',
            'username' => '',
            'email' => '',
            'password' => '',
        ];

        if ($this->input->post()) {
            $validatorFactory = new ValidatorFactory;
            $validator = $validatorFactory->make($this->input->post(), [
                'fullname' => 'required',
                'username' => 'required|alpha_dash|unique:user',
                'email' => 'required|email|unique:user',
                'password' => 'required|min:6',
                'g-recaptcha-response' => 'required',
            ]);

            $user_input['fullname'] = $this->input->post('fullname');
            $user_input['email'] = $this->input->post('email');
            $user_input['username'] = $this->input->post('username');
            $user_input['password'] = $this->input->post('password');

            if ($validator->fails()) {
                $errors = $validator->errors();
                $list_errors = $errors->all('<li>:message</li>');
            } else {
                DB::beginTransaction();
                try {
                    $gc = $this->input->post('g-recaptcha-response');
                    $client = new \GuzzleHttp\Client(['base_uri' => $_ENV['APP_URL']]);
                    $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                        'form_params' => [
                            'secret' => $_ENV['GCAPTCHA_V2_SECRET_KEY'],
                            'response' => $gc
                        ]
                    ]);

                    $body = $response->getBody();
                    $gresponse = json_decode($body->getContents());

                    if (!$gresponse->success) {
                        $list_errors = '<li>captcha salah</li>';
                        throw new Exception($body);
                    }

                    $user = new UserOrm();
                    $user->fullname = $user_input['fullname'];
                    $user->email = $user_input['email'];
                    $user->username = strtolower($user_input['username']);
                    $user->password = $user_input['password'];
                    $user->activation_code = generate_activation_code();
                    $user->save();

                    $user_role = new UserRole();
                    $user_role->user_id = $user->id;
                    $user_role->role_id = Role::where('rolename', Role::ROLE_NON_ADMIN)->first()->id;
                    $user_role->save();

                    $registration_sukses = 'registrasi berhasil, silahkan aktivasi';

                    $user_input = [
                        'fullname' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                    ];

                    try {
                        send_activation_email($user);
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }

                    DB::commit();
                    // $client->request('GET', site_url('pub/mailer') ); // RUN MAILER
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        render('User.registration', compact('registration_sukses','list_errors','user_input'));
    }

    public function activation($activation_code)
    {
        $user = UserOrm::where(['activation_code' => $activation_code, 'stts' => 0, 'is_sent_activation_code' => 1])->firstOrFail();
        $user->stts = 1;
        $user->save();
        $this->session->set_flashdata('activation_success', 'aktivasi berhasil, silahkan login');
        redirect('login');
    }
}
