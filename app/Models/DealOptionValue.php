<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealOptionValue extends Model
{
    protected $table = 'deal_option_values';
    public $timestamps = false;

    protected $fillable = [
        'deal_id',
        'item_option_id',
        'item_option_value_id',
        'value',
        'prefix_price',
        'price',
    ];

    public function deal()
    {
        return $this->belongsTo('App\Models\Deal','deal_id');
    }
    public function item_option()
    {
        return $this->belongsTo('App\Models\ItemOption','item_option_id');
    }
    public function item_option_values()
    {
        return $this->belongsTo('App\Models\ItemOptionValues','item_option_value_id');
    }

}
