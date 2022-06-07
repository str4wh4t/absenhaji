<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as DB;
use Grahes\Validator\ValidatorFactory;
use Orm\Absen AS AbsenOrm;
use Orm\UserAbsen;
use Orm\Role;

class Absen extends MY_Controller
{
    public function index()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        render('backend.absen.index');
    }

    public function tambah()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        $notif_sukses = '';
        $list_errors = [];

        $input = [
            'kode_absen' => '',
            'expired_at' => '',
        ];

        if ($this->input->post()) {
            $validatorFactory = new ValidatorFactory;
            $validator = $validatorFactory->make($this->input->post(), [
                'kode_absen' => 'required|unique:absen',
                'expired_at' => 'required|date',
            ]);

            $input['kode_absen'] = $this->input->post('kode_absen');
            $input['expired_at'] = $this->input->post('expired_at');

            if ($validator->fails()) {
                $errors = $validator->errors();
                $list_errors = $errors->all('<li>:message</li>');
            } else {
                DB::beginTransaction();
                try {
                    $absen = new AbsenOrm();
                    $absen->kode_absen = $input['kode_absen'];
                    $absen->expired_at = $input['expired_at'];
                    $absen->save();

                    DB::commit();

                    redirect('backend/absen/lihat/' . $absen->id);
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        $action = 'backend/absen/tambah';

        $qrcode = qrgenerate();

        render('backend.absen.form', compact('notif_sukses','list_errors','input', 'action', 'qrcode'));
    }

    public function generate()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        if (!$this->input->post()) {
            show_404();
        }

        $qrcode = qrgenerate();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($qrcode));
    }

    public function hapus()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        if (!$this->input->post()) {
            show_404();
        }

        $id = $this->input->post('id');
        $absen = AbsenOrm::findOrFail($id);
        echo $absen->delete();
    }

    public function scan()
    {
        render('backend.absen.scan');
    }

    public function doscan()
    {
        $notif_sukses = false;

        if ($this->input->post()) {
            $validatorFactory = new ValidatorFactory;
            $validator = $validatorFactory->make($this->input->post(), [
                'kode_absen' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $list_errors = $errors->all('<li>:message</li>');
            } else {
                DB::beginTransaction();
                try {
                    $user = $this->session->user;

                    $absen = AbsenOrm::where('kode_absen', $this->input->post('kode_absen'))->firstOrFail();
                    // if($absen->expired_at){

                    // }

                    $user_absen = new UserAbsen();
                    $user_absen->user_id = $user->id;
                    $user_absen->absen_id = $absen->id;
                    $user_absen->save();

                    DB::commit();

                    $notif_sukses = true;
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(compact('notif_sukses')));
    }

    public function lihat($id)
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        $absen = AbsenOrm::findOrFail($id);

        $qrcode = qrgenerate($absen->kode_absen);

        render('backend.absen.lihat', compact('absen','qrcode'));
    }

    public function riwayat()
    {
        render('backend.absen.riwayat');
    }
}