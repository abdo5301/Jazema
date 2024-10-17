<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $fillable = [
        'user_id',
        'name',
        'show_to_friends',
        'show_to_followers',
        'show_to_public',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
