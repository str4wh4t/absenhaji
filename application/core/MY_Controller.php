
<?php

class MY_Controller extends  CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _allow_role($rolename)
    {
        $role = $this->session->role;
        if (is_array($rolename)) {
            if (!in_array($role->rolename, $rolename)) {
                show_404();
            }
        } else {
            if ($role->rolename != $rolename) {
                show_404();
            }
        }
    }

    protected function _is_role($rolename)
    {
        $role = $this->session->role;
        $return = true;
        if (is_array($rolename)) {
            if (!in_array($role->rolename, $rolename)) {
                $return = false;
            }
        } else {
            if ($role->rolename != $rolename) {
                $return = false;
            }
        }

        return $return;
    }
}