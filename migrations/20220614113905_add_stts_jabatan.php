<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class AddSttsJabatan extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('ALTER TABLE `user` 
            ADD COLUMN `stts_jabatan` INT NOT NULL DEFAULT 0 AFTER `stts`;');
    }

    protected function down(): void
    {
        $this->execute('ALTER TABLE `user` 
            DROP COLUMN `stts_jabatan`;');
    }
}
