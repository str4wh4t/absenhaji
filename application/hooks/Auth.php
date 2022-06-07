<?php

use Orm\User;

class Auth
{
    public function verify()
    {
        $ci = &get_instance();
        $is_backend = ($ci->uri->segment(1) == 'backend' || $ci->uri->segment(1) == 'dashboard'); 
        if ($is_backend) {
            if (!$ci->session->has_userdata('user')) {
                redirect('login');
            }
        }
    }
}