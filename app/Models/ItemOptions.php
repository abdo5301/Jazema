<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOptions extends Model
{
    public $timestamps = true;
    protected $table = 'item_types';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'item_id',
        'type',
        'is_required',
        'status',
        'sort'
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
