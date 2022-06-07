<?php
namespace Orm;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    const LOGIN_WITH = 'email';

    public function userRole()
    {
        return $this->hasMany(UserRole::class);
    }

    public function userAbsen()
    {
        return $this->hasMany(UserAbsen::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }
}