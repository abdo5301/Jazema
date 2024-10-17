<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedAttributeValues extends Model
{
    public $timestamps = true;
    protected $table = 'selected_attribute_values';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'model_type',
        'model_id',
        'attribute_id',
        'attribute_value_id',
        'value',
    ];

    public function model()
    {
        $this->morphTo();
    }

    public function Attribute()
    {
        return $this->belongsTo('App\Models\Attribute','attribute_id');
    }
    public function values()
    {
        return $this->belongsTo('App\Models\AttributeValues','attribute_value_id');
    }
}
