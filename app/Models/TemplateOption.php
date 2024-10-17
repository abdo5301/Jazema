<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TemplateOption extends Model
{
    use SoftDeletes ,LogsActivity;
    public $timestamps = true;
    protected $table = 'template_options';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'user_id',
        'item_category_id',
        'name_ar',
        'name_en',
        'type',
        'is_required',
        'sort',
        'values',
        'status',
        'is_default',
        'staff_id'
    ];
    public function SetValuesAttribute($value)
    {
        return $this->attributes['values'] = @serialize($value);
    }
    public function getValuesAttribute($value)
    {
        if(!is_array($value))
            return $this->attributes['values'] = @unserialize($value);
        else
            return $this->attributes['values'] = $value;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
//    public function itemCategory()
//    {
//        return $this->belongsTo('App\Models\itemCategory','item_category_id');
//    }

public function itemCategory()
{
    return $this->belongsTo(ItemCategory::class,'item_category_id');
}
}
