<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemCategory extends Model
{
    use SoftDeletes, LogsActivity;
    public $timestamps = true;
    protected $table = 'item_categories';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'parent_id',
        'description_ar',
        'description_en',
        'icon',
        'status',
        'slug_ar',
        'slug_en',
        'sort',
        'staff_id'
    ];



    public static function Actives(){
        return ItemCategory::where('status','active')->orderBy('sort');
    }


    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
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
        return $this->belongsTo('App\Models\ItemCategory', 'parent_id');
    }
    public function attributes()
    {
        return $this->morphMany('App\Models\Attributes','model');
    }
}
