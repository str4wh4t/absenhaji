<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\Absen AS AbsenOrm;
use Orm\UserAbsen;
use Orm\Role;
use Grahes\Datatable\DtFactory;

class Absen extends CI_Controller
{
    public function index()
    {
        $dt = DtFactory::dt();

        $query = Absenorm::select(['id', 'kode_absen', 'created_at', 'expired_at']);

        $query = getEloquentSqlWithBindings($query);

        $dt->query($query);

        echo $dt->generate();
    }

    public function riwayat()
    {
        $role = $this->session->role;
        if ($role->rolename == Role::ROLE_ADMIN) {
            $query = UserAbsen::select(['user_absen.id', 'user.fullname', 'user.email',  'user_absen.created_at'])
            ->join('user','user.id', '=', 'user_absen.user_id');
        } else {
            $user = $this->session->user;
            $query = UserAbsen::select(['user_absen.id', 'user.fullname', 'user.email',  'user_absen.created_at'])
            ->join('user','user.id', '=', 'user_absen.user_id')
            ->where('user.id',$user->id);
        }
        $dt = DtFactory::dt();

        $query = getEloquentSqlWithBindings($query);
        $dt->query($query);

        echo $dt->generate();
    }

    public function riwayat_harian()
    {
        $role = $this->session->role;
        if ($role->rolename == Role::ROLE_ADMIN) {
            $query = UserAbsen::select(['user_absen.id', 'user.fullname', 'user.email',  'user_absen.created_at'])
            ->join('user','user.id', '=', 'user_absen.user_id');
        } else {
        }
        $query = $this->db->query("CALL report_harian('2024-03-03')");
        $dt = DtFactory::dt();

        // $query = getEloquentSqlWithBindings($query);
        $dt->query($query);

        echo $dt->generate();
    }
}