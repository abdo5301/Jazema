<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $modelPath = 'App\Models\Page';
    protected $table = 'pages';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
       'name_ar',
       'name_en',
       'content_ar',
       'content_en',
       'slug_ar',
       'slug_en',
       'staff_id',
    ];
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

}
