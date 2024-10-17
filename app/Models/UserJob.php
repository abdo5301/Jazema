<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserJob extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'user_jobs';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'staff_id',
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
    public function attributes()
    {
        return $this->morphMany('App\Models\Attribute','model');
    }
}
