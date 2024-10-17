<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemTypes extends Model
{
    use SoftDeletes ,LogsActivity;
    public $timestamps = true;
    protected $table = 'item_types';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'slug_en',
        'slug_ar',
        'parent_id',
        'sort',
        'icon',
        'status',
        'staff_id'
    ];

    public static function Actives()
    {
        return  ItemTypes::where('status','active');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\ItemTypes','parent_id');
    }
}
