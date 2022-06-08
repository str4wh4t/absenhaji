<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class Update_user extends AbstractMigration
{
    protected function up(): void
    {

        $this->execute("ALTER TABLE `user` 
            ADD COLUMN `bidang_id` INT NULL AFTER `password`, 
            ADD COLUMN `instansi_id` INT NULL AFTER `bidang_id`, 
            ADD COLUMN `jabatan_id` INT NULL AFTER `instansi_id`;");
    }

    protected function down(): void
    {
        $this->execute("ALTER TABLE `user` 
            DROP COLUMN `bidang_id`,
            DROP COLUMN `instansi_id`,
            DROP COLUMN `jabatan_id`;");
    }
}
