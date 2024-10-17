<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthSession extends Model
{
    protected $table = 'auth_session';
    public $timestamps = true;

//    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'guard_name',
        'access_token',
        'user_id',
        'ip',
        'user_agent',
        'updated_at'
    ];

}