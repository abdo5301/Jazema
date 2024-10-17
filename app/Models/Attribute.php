<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Attribute extends Model
{
    use SoftDeletes ,LogsActivity;
    public $timestamps = true;
    protected $table = 'attributes';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'model_type',
        'model_id',
        'item_type_id',
        'type',
        'is_required',
        'sort',
        'staff_id'
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
    public function itemType()
    {
        return $this->belongsTo('App\Models\ItemTypes','item_type_id');
    }
    public function values()
    {
        return $this->hasMany('App\Models\AttributeValues');
    }
}
