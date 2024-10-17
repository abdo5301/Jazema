<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use SoftDeletes, LogsActivity;
    protected $appends = ['authLiked','authWishlist'];
    public $timestamps = true;
    public $modelPath = 'App\Models\Item';
    protected $table = 'items';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'item_category_id',
        'item_type_id',
        'user_id',
        'owner_user_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'quantity',
        'views',
        'like',
        'comments',
        'share',
        'deals',
        'rank',
        'status',
         'slug_ar',
        'slug_en',
        'creatable_type',
        'creatable_id',
        'lat',
        'lng',
        'stage_id',
        'staff_id'
    ];


    public function getAuthSharedAttribute(){
        if(!Auth::check())
            return false;
        $item_shared = Item::where(['user_id'=>Auth::id(),'id'=>$this->id])->where('owner_user_id','!=',null)->first();
        
        if(!empty($item_shared))
            return true;
        else
            return false;
    }

    public function getAuthLikedAttribute(){
        if(!Auth::check())
        return false;

        $item_likes = $this->likes()->where('user_id',Auth::id())->first();

        if(!empty($item_likes))
            return true;
        else
            return false;
    }

    public function getAuthWishlistAttribute(){
        if(!Auth::check())
            return false;

        $item_likes = $this->wishlist()->where('user_id',Auth::id())->first();

        if(!empty($item_likes))
            return true;
        else
            return false;
    }

    public function getAuthCommentedAttribute(){
        if(!Auth::check())
            return false;
        $item_commented = $this->comment()->where('user_id',Auth::id())->first();
        if(!empty($item_commented))
            return true;
        else
            return false;
    }

    public function getAuthDealedAttribute(){
        if(!Auth::check())
            return false;
        $item_dealed = $this->item_deals()->where('user_id',Auth::id())->first();
        if(!empty($item_dealed))
            return true;
        else
            return false;
    }

    public function item_deals(){
        return $this->hasMany('App\Models\Deal', 'item_id');
    }

    public static function Actives(){
        return Item::where('status','active');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function owner_user()
    {
        return $this->belongsTo('App\Models\User', 'owner_user_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function item_type()
    {
        return $this->belongsTo('App\Models\ItemTypes', 'item_type_id');
    }

    public function item_category()
    {
        return $this->belongsTo('App\Models\ItemCategory', 'item_category_id');
    }

    ///////////////////////////////////////


    public function upload()
    {
        return $this->morphMany('App\Models\Upload', 'upload', 'model_type', 'model_id');
    }
    

    public function select_attribute()
    {
        return $this->morphMany('App\Models\SelectedAttributeValues', 'model','model_type', 'model_id');
    }

    public function option()
    {
        return $this->hasMany('App\Models\ItemOption', 'item_id');
    }

    public function ranks()
    {
        return $this->morphMany('App\Models\Rank', 'model', 'model_type', 'model_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'item_id');
    }
    public function likes()
    {
        return $this->hasMany('App\Models\ItemLikes', 'item_id');
    }
    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'item_id');
    }

    public function stage(){
        return $this->belongsTo('App\Models\Stage', 'stage_id');
    }
}
