<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User as UserOrm;
use Orm\UserRole;
use Orm\Role;
use Orm\Bidang;
use Orm\Instansi;
use Orm\Jabatan;
use Orm\Jabatan_struktural;
use Illuminate\Database\Capsule\Manager as DB;
use Grahes\Validator\ValidatorFactory;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\This;

class User extends MY_Controller
{
    public function index()
    {
        if ($this->_is_role(Role::ROLE_ADMIN)) {
            render('backend.User.index');
        } else {
            redirect('dashboard');
        }
    }

    public function tambah()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        $notif_sukses = '';
        $list_errors = [];

        $bidang = $this->table_refrensi(BIDANG);
        $instansi = $this->table_refrensi(INSTANSI);
        $jabatan = $this->table_refrensi(JABATAN);

        $input = [
            'fullname' => '',
            'username' => '',
            'email' => '',
            'password' => '',
            'bidang_id' => '',
            'instansi_id' => '',
            'jabatan_id' => '',
            'struktural_id' => '',
            'stts' => '',
        ];

        if ($this->input->post()) {
            $validatorFactory = new ValidatorFactory;
            $validator = $validatorFactory->make($this->input->post(), [
                'fullname' => 'required',
                'username' => 'required|alpha_dash|unique:user',
                'email' => 'required|email|unique:user',
                'password' => 'required|min:6',
            ]);

            $input['fullname'] = $this->input->post('fullname');
            $input['email'] = $this->input->post('email');
            $input['username'] = $this->input->post('username');
            $input['password'] = $this->input->post('password');
            $input['bidang_id'] = $this->input->post('bidang');
            $input['instansi_id'] = $this->input->post('instansi');
            $input['jabatan_id'] = $this->input->post('jabatan');
            $input['struktural_id'] = $this->input->post('struktural');

            if ($validator->fails()) {
                $errors = $validator->errors();
                $list_errors = $errors->all('<li>:message</li>');
            } else {
                DB::beginTransaction();
                try {
                    $user = new UserOrm();
                    $user->fullname = $input['fullname'];
                    $user->email = $input['email'];
                    $user->username = strtolower($input['username']);
                    $user->password = $input['password'];
                    $user->bidang_id = empty($input['bidang_id']) ? null : $input['bidang_id'];
                    $user->instansi_id = empty($input['instansi_id']) ? null : $input['instansi_id'];
                    $user->jabatan_id = empty($input['jabatan_id']) ? null : $input['jabatan_id'];
                    $user->struktural_id = empty($input['struktural_id']) ? null : $input['struktural_id'];

                    $user->activation_code = generate_activation_code();

                    $user->save();

                    $user_role = new UserRole();
                    $user_role->user_id = $user->id;
                    $user_role->role_id = Role::where('rolename', Role::ROLE_NON_ADMIN)->first()->id;
                    $user_role->save();

                    DB::commit();

                    $notif_sukses = 'simpan berhasil';

                    $input = [
                        'fullname' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                        'bidang_id' => '',
                        'instansi_id' => '',
                        'jabatan_id' => '',
                        'struktural_id' => '',
                        'stts' => '',
                    ];
                } catch (Exception $e) {
                    DB::rollback();
                    dd($e->getMessage());
                }
            }
        }

        $action = 'backend/user/tambah';
        $title = 'Tambah User';

        render('backend.User.form', compact('notif_sukses','list_errors','input', 'action', 'title', 'bidang', 'instansi', 'jabatan'));
    }

    public function edit($id)
    {
        if ($this->_is_role(Role::ROLE_ADMIN)) {
            $user = UserOrm::findOrFail($id);
        }

        if ($this->_is_role(Role::ROLE_NON_ADMIN)) {
            $user = $this->session->user;
            $user = UserOrm::findOrFail($user->id);
            $id = $user->id;
        }

        $bidang = $this->table_refrensi(BIDANG);
        $instansi = $this->table_refrensi(INSTANSI);
        $jabatan = $this->table_refrensi(JABATAN);
        $jabatan_struktural = $this->table_refrensi(JABATAN_STRUKTURAL);

        // print_r($bidang);

        $notif_sukses = '';
        $list_errors = [];

        $input = [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->password,
            'bidang_id' => $user->bidang_id,
            'instansi_id' => $user->instansi_id,
            'jabatan_id' => $user->jabatan_id,
            'stts' => $user->stts,
            'struktural_id' => $user->struktural_id,
        ];

        if ($this->input->post()) {
            $validatorFactory = new ValidatorFactory;
            $validator = $validatorFactory->make($this->input->post(), [
                'fullname' => 'required',
                // 'bidang_id' => 'required',
                // 'instansi_id' => 'required',
                // 'jabatan_id' => 'required',
                'username' => [
                    'required',
                    'alpha_dash',
                    Rule::unique('user')->ignore($id)
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('user')->ignore($id)
                ],
                'password' => 'required|min:6',
            ]);

            $input['fullname'] = $this->input->post('fullname');
            $input['email'] = $this->input->post('email');
            $input['username'] = $this->input->post('username');
            $input['password'] = $this->input->post('password');
            $input['bidang_id'] = $this->input->post('bidang');
            $input['instansi_id'] = $this->input->post('instansi');
            $input['jabatan_id'] = $this->input->post('jabatan');
            $input['struktural_id'] = $this->input->post('struktural');

            if ($validator->fails()) {
                $errors = $validator->errors();
                $list_errors = $errors->all('<li>:message</li>');
            } else {
                DB::beginTransaction();
                try {
                    $user->fullname = $input['fullname'];
                    $user->email = $input['email'];
                    $user->username = $input['username'];
                    $user->password = $input['password'];
                    $user->bidang_id = empty($input['bidang_id']) ? null : $input['bidang_id'];
                    $user->instansi_id = empty($input['instansi_id']) ? null : $input['instansi_id'];
                    $user->jabatan_id = empty($input['jabatan_id']) ? null : $input['jabatan_id'];
                    $user->struktural_id = empty($input['struktural_id']) ? null : $input['struktural_id'];
                    $user->save();

                    DB::commit();

                    $notif_sukses = 'simpan berhasil';

                    $input = [
                        'id' => $user->id,
                        'fullname' => $user->fullname,
                        'username' => $user->username,
                        'email' => $user->email,
                        'password' => $user->password,
                        'bidang_id' => $user->bidang_id,
                        'instansi_id' => $user->instansi_id,
                        'jabatan_id' => $user->jabatan_id,
                        'struktural_id' => $user->struktural_id,
                        'stts' => $user->stts,
                    ];
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        $action = 'backend/user/edit/' . $id;
        $title = 'Edit User';

        render('backend.User.form', compact(
                    'notif_sukses',
                    'list_errors',
                    'input', 
                    'action', 
                    'title', 
                    'bidang', 
                    'instansi', 
                    'jabatan',
                    'jabatan_struktural'
                ));
    }

    public function hapus()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        if (!$this->input->post()) {
            show_404();
        }

        $id = $this->input->post('id');
        $user = UserOrm::findOrFail($id);
        echo $user->delete();
    }

    public function setting()
    {
        $user = $this->session->user;
        $this->edit($user->id);
    }

    public function activation($id)
    {
        $user = UserOrm::findOrFail($id);
        $user->stts = 1;
        $user->save();
        $this->session->set_flashdata('activation_success', 'aktivasi berhasil');
        redirect('backend/user/edit/' . $id);
    } 

    public function table_refrensi($pilihan)
    {
        $reff = [
            BIDANG => [
                'data' => Bidang::all(),
                'field' => 'bidangname'
            ],
            INSTANSI => [
                'data' => Instansi::all(),
                'field' => 'instansiname'
            ],
            JABATAN => [
                'data' => Jabatan::all(),
                'field' => 'jabatanname'
            ],
        ];

        $data = $reff[$pilihan]['data'];
        $field = $reff[$pilihan]['field'];

        $record = ['' => ''];
        foreach ($data as $r) {
                    BIDANG =>[
                            "data" => Bidang::all(),
                            "field" => "bidangname"
                    ],
                    INSTANSI =>[
                            "data" => Instansi::all(),
                            "field" => "instansiname"
                    ],
                    JABATAN =>[
                            "data" => Jabatan::all(),
                            "field" => "jabatanname"
                    ],
                    JABATAN_STRUKTURAL =>[
                            "data" => Jabatan_struktural::all(),
                            "field" => "jabatanname"
                    ],
            }

        $data   = $reff[$pilihan]['data'];
        $field  = $reff[$pilihan]['field'];
    
        $record = array(''=> '');
        $record = array(0 => 'Tidak Tersedia');
        foreach($data as $r){
            $record[$r->id] = $r->$field;
        }
        return $record;
    }
}
