<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{

    protected $table = 'contact_us';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'subject',
        'message',
        'read_by_staff_id'
    ];


    public function readBy(){
        return $this->belongsTo('App\Models\Staff','read_by_staff_id');
    }


}