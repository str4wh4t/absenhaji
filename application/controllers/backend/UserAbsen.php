<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\UserAbsen As UserAbsenOrm;

class UserAbsen extends CI_Controller
{
    public function index()
    {
        redirect('absen/riwayat');
    }    

    public function hapus()
    {
        if (!$this->input->post()) {
            show_404();
        }

        $role = $this->session->role;
        if ($role->rolename != Role::ROLE_ADMIN) {
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