<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemLikes extends Model
{
    public $timestamps = true;
    public $modelPath = 'App\Models\ItemLikes';
    protected $table = 'item_likes';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable =[
        'user_id',
        'item_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

}
