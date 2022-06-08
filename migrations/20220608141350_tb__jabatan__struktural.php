<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class Tb_jabatan_struktural extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('ALTER TABLE `user` 
            ADD COLUMN `struktural_id` INT NULL AFTER `password`;');
        
        $this->table('ref_jabatan_struktural')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('jabatanname', 'string')
            ->create();

        $this->insert('ref_jabatan_struktural',[
            ['jabatanname' => 'Pengarah'],
            ['jabatanname' => 'Ketua'],
            ['jabatanname' => 'Wakil Ketua I'],
            ['jabatanname' => 'Wakil Ketua II'],
            ['jabatanname' => 'Sekretaris'],
            ['jabatanname' => 'Wakil Sekretaris I'],
            ['jabatanname' => 'Wakil Sekretaris II'],
            ['jabatanname' => 'Kabid Penerimaan dan Pemberangkatan'],
            ['jabatanname' => 'Wakabid Penerimaan dan Pemberangkatan'],
            ['jabatanname' => 'Kabid Dokumen'],
            ['jabatanname' => 'Wakabid Dokumen'],
            ['jabatanname' => 'Kabid Pembinaan Jemaah Haji'],
            ['jabatanname' => 'Kabid Perbekalan'],
            ['jabatanname' => 'Wakabid Perbekalan'],
            ['jabatanname' => 'Kabid Akomodasi'],
            ['jabatanname' => 'Wakabid Akomodasi'],
            ['jabatanname' => 'Kabid Bea dan Cukai'],
            ['jabatanname' => 'Kabid Imigrasi'],
            ['jabatanname' => 'Kabid Kesehatan'],
            ['jabatanname' => 'Kabid Keamanan'],
            ['jabatanname' => 'Wakabid Keamanan'],
            ['jabatanname' => 'Kabid Penerbangan'],
            ['jabatanname' => 'Wakabid Penerbangan']
        ]);
    }

    protected function down(): void
    {
        $this->execute('ALTER TABLE `user` 
            DROP COLUMN `struktural_id`;');
        
        $this->table('ref_jabatan_struktural')->drop();
    }
}
