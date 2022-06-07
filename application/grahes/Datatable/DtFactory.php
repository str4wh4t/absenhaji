<?php
namespace Grahes\Datatable;

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\MySQL;

class DtFactory
{
    public static function dt()
    {
        $config = ['host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'database' => $_ENV['DB_NAME']];

        return new Datatables( new MySQL($config) );
    }
}