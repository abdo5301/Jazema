<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $modelPath = 'App\Models\Chat';
    protected $table = 'chats';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
    ];
    public function fromUser()
    {
        return $this->belongsTo('App\Models\User','from_user_id');
    }
    public function toUser()
    {
        return $this->belongsTo('App\Models\User','to_user_id');
    }

}
