<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Service extends Model
{

    protected $table = 'services';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'slug_ar',
        'slug_en',
        'image',
        'staff_id'
    ];
    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'image',
        'slug_ar',
        'slug_en',
        'staff_id'
    ];
    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }

}