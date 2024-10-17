<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Comment extends Model
{
    use SoftDeletes, LogsActivity;
    public $modelPath = 'App\Models\Comment';
    protected $table = 'comments';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'item_id',
        'user_id',
        'comment',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\item', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
