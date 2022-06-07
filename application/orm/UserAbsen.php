<?php
namespace Orm;

use Illuminate\Database\Eloquent\Model;

class UserAbsen extends Model
{
    protected $table = 'user_absen';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absen()
    {
        return $this->belongsTo(Absen::class);
    }
}