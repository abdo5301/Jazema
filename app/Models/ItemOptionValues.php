<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemOptionValues extends Model
{
    use SoftDeletes ,LogsActivity;
    public $timestamps = true;
    protected $table = 'item_option_values';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'item_option_id',
        'price_prefix',
        'price',
        'status',
    ];

    public function itemOption()
    {
        return $this->belongsTo('App\Models\ItemOption');
    }
}
