<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class AddSttsActiveUser extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('ALTER TABLE `user` 
            ADD COLUMN `stts` INT NOT NULL DEFAULT 0 AFTER `password`,
            ADD COLUMN `activation_code` VARCHAR(255) NULL AFTER `stts`,
            ADD COLUMN `is_sent_activation_code` INT NOT NULL DEFAULT 0 AFTER `activation_code`;');

        $this->update('user',['stts' => 1, 'is_sent_activation_code' => 1],['id' => 1]); // SET ADMIN TO ACTIVE
    }

    protected function down(): void
    {
        $this->execute('ALTER TABLE `user` 
            DROP COLUMN `stts`,
            DROP COLUMN `activation_code`,
            DROP COLUMN `is_sent_activation_code`;');
    }
}
