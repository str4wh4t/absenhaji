<?php
namespace Orm;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';

    const ROLE_ADMIN = 'admin';
    const ROLE_NON_ADMIN = 'non_admin';
}