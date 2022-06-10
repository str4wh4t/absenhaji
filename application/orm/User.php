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

//// refrensi
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function jabatan_struktural()
    {
        return $this->belongsTo(Jabatan_struktural::class);
    }
}