<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Relations extends Model
{
    use SoftDeletes, LogsActivity;

    public $modelPath = 'App\Models\Relations';
    protected $table = 'relations';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'user_id',
        'to_user_id',
        'type',
        'status',

    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $logAttributes = [
        'user_id',
        'to_user_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function to_user()
    {
        return $this->belongsTo('App\Models\User', 'to_user_id');
    }



}
