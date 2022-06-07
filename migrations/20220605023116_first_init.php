<?php

declare(strict_types=1);

use Phoenix\Database\Element\Index;
use Phoenix\Migration\AbstractMigration;

final class FirstInit extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('user')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('fullname', 'string')
            ->addColumn('username', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('username', Index::TYPE_UNIQUE)
            ->addIndex('email', Index::TYPE_UNIQUE)
            ->create();

        $this->table('role')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('rolename', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('rolename', Index::TYPE_UNIQUE)
            ->create();

        $this->table('user_role')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('user_id', 'integer')
            ->addColumn('role_id', 'integer')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id', 'role_id'], Index::TYPE_UNIQUE)
            ->create();

        $this->table('user_role')
            ->addForeignKey('user_id', 'user', 'id', 'cascade', 'no action')
            ->addForeignKey('role_id', 'role', 'id', 'cascade', 'no action')
            ->save();

        $this->insert('user',[
            [
                'fullname' => 'administrator',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '123456',
                'created_at' => date('Y-m-d'),
            ]
        ]);

        $this->insert('role',[
            [
                'rolename' => 'admin',
                'created_at' => date('Y-m-d'),
            ],
            [
                'rolename' => 'non_admin',
                'created_at' => date('Y-m-d'),
            ]
        ]);

        $this->insert('user_role',[
            [
                'user_id' => 1,
                'role_id' => 1,
                'created_at' => date('Y-m-d'),
            ]
        ]);

        $this->table('absen')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('kode_absen', 'string')
            ->addColumn('expired_at', 'datetime')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('kode_absen', Index::TYPE_UNIQUE)
            ->create();

        $this->table('user_absen')
            ->addColumn('id', 'integer', ['autoincrement' => true])
            ->addColumn('user_id', 'integer')
            ->addColumn('absen_id', 'integer')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id', 'absen_id'], Index::TYPE_UNIQUE)
            ->create();

        $this->table('user_absen')
            ->addForeignKey('user_id', 'user', 'id', 'cascade', 'no action')
            ->addForeignKey('absen_id', 'absen', 'id', 'cascade', 'no action')
            ->save();
    }

    protected function down(): void
    {
        $this->table('user_absen')
            ->drop();

        $this->table('absen')
            ->drop();

        $this->table('user_role')
            ->drop();

        $this->table('role')
            ->drop();

        $this->table('user')
            ->drop();
    }
}
