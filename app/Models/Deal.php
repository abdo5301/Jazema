<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Deal extends Model
{
    use SoftDeletes, LogsActivity;

    public $modelPath = 'App\Models\Deal';
    protected $table = 'deals';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'item_id',
        'item_owner_id',
        'user_id',
        'status',
        'total_price',
        'notes',
        'staff_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $logAttributes = [
        'item_id',
        'item_owner_id',
        'user_id',
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
}

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

     
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'item_owner_id');
    }
    public function options()
    {
        return $this->hasMany('App\Models\DealOptionValue','deal_id');
    }
}
