<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Grahes\Validator\ValidatorFactory;
use Illuminate\Database\Capsule\Manager as DB;
use Orm\Absen as AbsenOrm;
use Orm\Role;
use Orm\User;
use Orm\UserAbsen;

use function PHPUnit\Framework\isNull;

class Absen extends MY_Controller
{
    public function index()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        render('backend.Absen.index');
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
                'expired_at' => 'required|date|after:now',
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

        render('backend.Absen.form', compact('notif_sukses', 'list_errors', 'input', 'action', 'qrcode'));
    }

    public function generate()
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        if (! $this->input->post()) {
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
        if (! $this->input->post()) {
            show_404();
        }

        $id = $this->input->post('id');
        $absen = AbsenOrm::findOrFail($id);
        echo $absen->delete();
    }

    public function scan()
    {
        render('backend.Absen.scan');
    }

    public function doscan()
    {
        $notif_sukses = false;
        $msg = '';

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
                    $expired_at = Carbon::createFromFormat('Y-m-d H:i:s', $absen->expired_at);

                    if (Carbon::now()->isSameDay($expired_at)) {
                        if (Carbon::now()->gt($expired_at)) {
                            //
                            $msg = 'expired';
                            // throw new Exception($msg);
                        }
                        $user_absen = new UserAbsen();
                        $user_absen->user_id = $user->id;
                        $user_absen->absen_id = $absen->id;
                        $user_absen->stts = empty($msg) ? 1 : 0; // 1 : ONTIME ; 0 : terlambat
                        $user_absen->save();

                        DB::commit();

                        $notif_sukses = true;
                    } else {
                        $msg = 'invalid';
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    // dd($e->getMessage());
                }
            }
        }

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(compact('notif_sukses', 'msg')));
    }

    public function lihat($id)
    {
        $this->_allow_role(Role::ROLE_ADMIN);
        $absen = AbsenOrm::findOrFail($id);

        $qrcode = qrgenerate($absen->kode_absen);

        render('backend.Absen.lihat', compact('absen', 'qrcode'));
    }

    public function riwayat()
    {
        render('backend.Absen.riwayat');
    }
    public function riwayat_harian()
    {
        // dd($tgl);
        $tgl = ($this->input->get('date'));
        $data_absen = false;
        $field_names = false;

        if($tgl){
            $data  = $this->db->query("CALL report_harian('$tgl');");
            $data_absen = $data->result();
            $data_field = $data->field_data();
            
            $field_names = array();
            foreach ($data_field as $field) {
                $field_names[] = $field->name;
            }
        }
       
    
        render('backend.Absen.riwayat_harian', compact('tgl', 'data_absen', 'field_names'));
    }

    public function cetak($date_start, $date_end, $is_html = true)
    {
        $this->_allow_role(Role::ROLE_ADMIN);

        try {
            $date_start = Carbon::parse($date_start);
            $date_end = Carbon::parse($date_end);

            if ($date_start->gt($date_end)) {
                throw new Exception('Tanggal awal tidak boleh mendahului');
            }
        } catch (\Exception $e) {
            show_error('Terjadi kesalahan data');
        }

        $nama_file = 'absen_petugas_haji';
        // $user =  User::whereHas('absen')->get();

        $user_list = User::orderBy('bidang_id')
                            ->orderBy('instansi_id')
                            ->orderBy('jabatan_id')
                            ->orderBy('struktural_id')
                            ->orderBy('fullname')
                            ->whereKeyNot(1) // EXCEPT ADMINISTRATOR
                            ->get();

        // $abs = AbsenOrm::whereDate('created_at', Carbon::createFromFormat('Y-m-d H:i:s',  '2022-06-10 06:45:21'))->first();

        // $absen_list = AbsenOrm::all();

        // $absen_tgl_list = $absen_list->groupBy('kode_absen');

        $period = CarbonPeriod::create($date_start, $date_end);

        if ($is_html) {
            render('backend.Absen.cetak_html', compact(
                'nama_file',
                'user_list',
                'period',
                'date_start',
                'date_end'
            ));
        } else {
            render('backend.Absen.cetak_xls', compact(
                'nama_file',
                'user_list',
                'period',
                'date_start',
                'date_end'
            ));
        }
    }

    public function manual($user_id, $absen_id)
    {
        $this->_allow_role(Role::ROLE_ADMIN);

        $user_absen = UserAbsen::where(['absen_id' => $absen_id, 'user_id' => $user_id])->first();
        if (empty($user_absen)) {
            try {
                $absen = AbsenOrm::findOrFail($absen_id);
                $user_absen = new UserAbsen();
                $user_absen->absen_id = $absen_id;
                $user_absen->user_id = $user_id;
                $user_absen->stts = 1;
                $user_absen->created_at = $absen->expired_at; // DIANGGAP ABSEN PALING AKHIR
                $user_absen->save();
            } catch (Exception $e) {
                show_error($e->getMessage());
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
}
