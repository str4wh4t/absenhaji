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
                    $user = new UserOrm();
                    $user->fullname = $user_input['fullname'];
                    $user->email = $user_input['email'];
                    $user->username = $user_input['username'];
                    $user->password = $user_input['password'];
                    $user->save();

                    $user_role = new UserRole();
                    $user_role->user_id = $user->id;
                    $user_role->role_id = Role::where('rolename', Role::ROLE_NON_ADMIN)->first()->id;
                    $user_role->save();

                    $registration_sukses = 'registrasi berhasil';

                    $user_input = [
                        'fullname' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                    ];

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        render('User.registration', compact('registration_sukses','list_errors','user_input'));
    }

    public function absensi()
    {
    }
}
