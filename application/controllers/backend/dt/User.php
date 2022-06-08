<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Orm\User as UserOrm;
use Orm\Role;
use Grahes\Datatable\DtFactory;

class User extends CI_Controller
{
    public function index()
    {
        $dt = DtFactory::dt();

        $query = UserOrm::whereHas(
            'role', function($q) {
                $q->where('rolename', Role::ROLE_NON_ADMIN);
            }
        )->select(['id', 'fullname', 'username', 'email', 'password', 'created_at', 'stts']);

        $query = getEloquentSqlWithBindings($query);

        $dt->query($query);

        echo $dt->generate();
    }
}
