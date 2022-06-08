<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User;

class Pub extends CI_Controller
{
    public function index()
    {
        show_404();
    }

    public function mailer()
    {
        try {
            $user_list = User::where(['stts' => 0, 'is_sent_activation_code' => 0])->get();
            send_activation_email_bulk($user_list);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}