<?php
namespace Orm;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen';

    public function userAbsen()
    {
        return $this->hasMany(UserAbsen::class);
    }
}