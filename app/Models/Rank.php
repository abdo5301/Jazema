<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public $modelPath = 'App\Models\Rank';
    protected $table = 'ranks';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'deal_id',
        'comment',
        'rank',
    ];
    public function model()
    {
        return $this->morphTo();
    }

    public function deal(){
        return $this->belongsTo('App\Models\Deal');
    }

}
