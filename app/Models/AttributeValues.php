<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeValues extends Model
{
    use SoftDeletes, LogsActivity;
    public $timestamps = true;
    protected $table = 'attribute_values';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'attribute_id',
        'sort',
    ];

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute', 'attribute_id');
    }
}
