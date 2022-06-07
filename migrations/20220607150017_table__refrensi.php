<?php

declare(strict_types=1);


use Phoenix\Database\Element\Index;
use Phoenix\Migration\AbstractMigration;

final class Table_refrensi extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('ref_bidang')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('bidangname', 'string')
            ->create();

        $this->table('ref_jabatan')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('jabatanname', 'string')
            ->create();
       
        $this->table('ref_instansi')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('instansiname', 'string')
            ->create();

        $this->insert('ref_bidang',[
            ['bidangname' => 'BIDANG SEKRETARIAT'],
            ['bidangname' => 'BIDANG DOKUMEN'],
            ['bidangname' => 'BIDANG AKOMODASI'],
            ['bidangname' => 'BIDANG PEMBINAAN'],
            ['bidangname' => 'BIDANG PENERIMAAN DAN PEMBERANGKATAN'],
            ['bidangname' => 'BIDANG PERBEKALAN'],
            ['bidangname' => 'BIDANG IMIGRASI'],
            ['bidangname' => 'BIDANG BEA DAN CUKAI'],
            ['bidangname' => 'BIDANG KEAMANAN'],
            ['bidangname' => 'BIDANG PENERBANGAN'],
            ['bidangname' => 'BIDANG KESEHATAN']
        ]);

        $this->insert('ref_jabatan',[
            ['jabatanname' => 'Anggota'],
            ['jabatanname' => 'Koodinator Pembinaan Petugas dan Jemaah'],
            ['jabatanname' => 'Koodinator Pengawasan Catering'],
            ['jabatanname' => 'Koordiantor Pulahta'],
            ['jabatanname' => 'Koordinator'],
            ['jabatanname' => 'Koordinator Humas'],
            ['jabatanname' => 'Koordinator Keuangan'],
            ['jabatanname' => 'Koordinator Koper'],
            ['jabatanname' => 'Koordinator Living Cost'],
            ['jabatanname' => 'Koordinator Pemberangkatan'],
            ['jabatanname' => 'Koordinator Penelitian dan Penyelesaian Paspor'],
            ['jabatanname' => 'Koordinator Penempatan Jemaah'],
            ['jabatanname' => 'Koordinator Penerimaan'],
            ['jabatanname' => 'Koordinator Pramanifest dan Pengumpulan Data'],
            ['jabatanname' => 'Koordinator Steril dan Sweeping'],
            ['jabatanname' => 'Koordinator Tata Usaha'],
            ['jabatanname' => 'Urusan Dalam']
        ]);

        $this->insert('ref_instansi',[
            ['instansiname' => 'Angkasa Pura I '],
            ['instansiname' => 'Bea dan Cukai Surakarta'],
            ['instansiname' => 'Dinkes Prov. Jateng'],
            ['instansiname' => 'Kanim Kelas I TPI Surakarta'],
            ['instansiname' => 'Kanwil Kemenag Prov. DIY'],
            ['instansiname' => 'Kanwil Kemenag Prov. Jateng'],
            ['instansiname' => 'Kemenag Kab Jepara'],
            ['instansiname' => 'Kemenag Kab. Banjarnegara'],
            ['instansiname' => 'Kemenag Kab. Banyumas'],
            ['instansiname' => 'Kemenag Kab. Batang'],
            ['instansiname' => 'Kemenag Kab. Blora'],
            ['instansiname' => 'Kemenag Kab. Boyolali'],
            ['instansiname' => 'Kemenag Kab. Brebes'],
            ['instansiname' => 'Kemenag Kab. Cilacap'],
            ['instansiname' => 'Kemenag Kab. Demak'],
            ['instansiname' => 'Kemenag Kab. Grobogan'],
            ['instansiname' => 'Kemenag Kab. Karanganyar'],
            ['instansiname' => 'Kemenag Kab. Kendal'],
            ['instansiname' => 'Kemenag Kab. Klaten'],
            ['instansiname' => 'Kemenag Kab. Magelang'],
            ['instansiname' => 'Kemenag Kab. Pati'],
            ['instansiname' => 'Kemenag Kab. Purbalingga'],
            ['instansiname' => 'Kemenag Kab. Purworejo'],
            ['instansiname' => 'Kemenag Kab. Semarang'],
            ['instansiname' => 'Kemenag Kab. Sragen'],
            ['instansiname' => 'Kemenag Kab. Sukoharjo'],
            ['instansiname' => 'Kemenag Kab. Tegal'],
            ['instansiname' => 'Kemenag Kab. Temanggung'],
            ['instansiname' => 'Kemenag Kab. Wonogiri'],
            ['instansiname' => 'Kemenag Kab. Wonosobo'],
            ['instansiname' => 'Kemenag Kota Semarang'],
            ['instansiname' => 'Kemenag Kota Surakarta'],
            ['instansiname' => 'Kemenag Kota Tegal'],
            ['instansiname' => 'KKP Kelas II Semarang'],
            ['instansiname' => 'Kodim 0724/ Boyolali'],
            ['instansiname' => 'Lanud Adi Soemarmo'],
            ['instansiname' => 'PAHD Boyolali'],
            ['instansiname' => 'Polres Boyolali'],
            ['instansiname' => 'RSUD Moewardi'],
            ['instansiname' => 'Swasta']
        ]);


    }

    protected function down(): void
    {
        $this->table('ref_bidang')->drop();
        $this->table('ref_jabatan')->drop();
        $this->table('ref_instansi')->drop();
    }
}
