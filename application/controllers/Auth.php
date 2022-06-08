<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User;

class Auth extends CI_Controller
{
    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $login_salah = '';
        $login_with = User::LOGIN_WITH;

        if ($this->session->has_userdata('user')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $identity = $this->input->post($login_with);
            $password = $this->input->post('password');

            $user = User::where([$login_with => $identity, 'password' => $password, 'stts' => 1])->first();
            if (!empty($user)) {
                $userdata = [
                    'user' => $user,
                    'role' => $user->role()->first(),
                ];
                $this->session->set_userdata($userdata);
                redirect('dashboard');
            } else {
                $login_salah = 'user tidak ditemukan';
            }
        }

        render('Auth.login', ['login_salah' => $login_salah, 'login_with' => $login_with]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
