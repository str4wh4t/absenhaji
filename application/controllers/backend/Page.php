<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        render('backend.page.dashboard');
    }
}
