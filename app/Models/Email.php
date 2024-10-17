<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $modelPath = 'App\Models\Email';
    protected $table = 'emails';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'deleted_from',
        'deleted_to',
    ];
    protected $fillable = [
        'from_id',
        'to_id',
        'item_id',
        'mobile',
        'email',
        'name',
        'message',
        'subject',
        'deleted_from',
        'deleted_to',
    ];
    public function from_user()
    {
        return $this->belongsTo(User::class, 'from_id');
    }
    public function to_user()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
