<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class AddSttsAbsen extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('ALTER TABLE `user_absen` 
            ADD COLUMN `stts` INT NULL AFTER `absen_id`;');
    }

    protected function down(): void
    {
        $this->execute('ALTER TABLE `user_absen` 
            DROP COLUMN `stts`;');
    }
}
