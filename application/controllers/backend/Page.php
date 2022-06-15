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
        // dd());
        render('backend.Page.dashboard');

    }

    public function rekap()
    {
        $absen = [
            6=>'10',
            8=>'11',
            10=>'12',
            11=>'13',
            12=>'14',
            13=>'15',
        ];
        
        $this->db->select('a.id, a.fullname, c.bidangname, d.instansiname, e.jabatanname, , f.jabatanname AS jabatan_struktural')->from('user a');
        
        foreach($absen as $id_absen=>$tgl){
            $this->db->join('user_absen b'.$id_absen, 'ON a.id = b'.$id_absen.'.user_id AND b'.$id_absen.'.absen_id ='.$id_absen, 'left');
            $this->db->select("if(b".$id_absen.".created_at IS NULL,'-', TIME(b".$id_absen.".created_at))  AS Tgl_".$tgl, null);
        }
            $this->db->join('ref_bidang c','ON a.bidang_id = c.id', 'left' );
            $this->db->join('ref_instansi d','ON a.instansi_id = d.id', 'left' );
            $this->db->join('ref_jabatan e','ON a.jabatan_id = e.id', 'left' );
            $this->db->join('ref_jabatan_struktural f','ON a.struktural_id = f.id', 'left' );
            $this->db->order_by('a.bidang_id', 'ASC');
            $this->db->order_by('a.fullname', 'ASC');
            $this->db->where('a.id !=', 1
        );

        $sql = $this->db->get_compiled_select();
        
        print_r( $sql);
        
    }

}
