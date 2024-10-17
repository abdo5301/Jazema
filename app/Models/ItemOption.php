<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemOption extends Model
{
    use SoftDeletes, LogsActivity;
    public $timestamps = true;
    protected $table = 'item_options';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'item_id',
        'name_ar',
        'name_en',
        'type',
        'is_required',
        'sort',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }
    public function values(){
        return $this->hasMany('App\Models\ItemOptionValues','item_option_id');
    }
}
