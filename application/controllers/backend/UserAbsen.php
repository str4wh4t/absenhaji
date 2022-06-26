<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\UserAbsen As UserAbsenOrm;
use Orm\Role;

class UserAbsen extends MY_Controller
{
    public function index()
    {
        redirect('absen/riwayat');
    }    

    public function hapus()
    {
        $this->_allow_role(Role::ROLE_ADMIN);

        if (!$this->input->post()) {
            show_404();
        }

        $id = $this->input->post('id');
        $user_absen = UserAbsenOrm::findOrFail($id);
        echo $user_absen->delete();
    }

    public function lihat($id)
    {
        $user_absen = UserAbsenOrm::findOrFail($id);

        $qrcode = qrgenerate($user_absen->absen->kode_absen);

        render('backend.UserAbsen.lihat', compact('user_absen','qrcode'));
    }
}