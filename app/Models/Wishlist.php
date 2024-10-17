<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;


class Wishlist extends Model
{
    use SoftDeletes;
    protected $table = 'wishlist';
    public $timestamps = true;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'item_id',
        'user_id'

    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

}