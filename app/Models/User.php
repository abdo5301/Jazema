<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Relation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Auth;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, LogsActivity, HasApiTokens;

    public $modelPath = 'App\Models\User';
    protected $table = 'users';
    public $timestamps = true;

    protected $dates = [
        'lastlogin',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'user_job_id',
        'type',
        'company_name',
        'company_business',
        'firstname',
        'lastname',
        'email',
        'password',
        'gender',
        'phone',
        'mobile',
        'mobile2',
        'mobile3',
        'area_id',
        'address',
        'lat',
        'lng',
        'location',
        'about',
        'rank',
        'image',
        'facebook',
        'youtube',
        'linkedin',
        'instgram',
        'google',
        'interisted_categories',
        'status',
        'slug',
        'staff_id',
        'views',
    ];

    protected $hidden = array('password');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $logAttributes = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'password',
        'pin_code',
        'image',
        'gender',
        'birthdate',
        'status',

    ];

    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'user_id');
    }



    public function followers()
    {
        return Relations::where(['to_user_id' => Auth::id(), 'type' => 'follow']);
    }

    public function following()
    {
        return Relations::where(['user_id' => Auth::id(), 'type' => 'follow']);
    }
    public function friendsOfMine()
    { //return all friends which i requested
        return $this->belongsToMany(User::class,'relations','user_id','to_user_id')->where(['relations.type' => 'friend', 'relations.status' => 'accept']);
    }
    public function friendsOf()
    {
        //return all users which requested me as there friends
        return $this->belongsToMany(User::class,'relations','to_user_id','user_id')->where(['relations.type' => 'friend', 'relations.status' => 'accept']);
    }
//    public function friend()
//    {
//        return $this->friendsOfMine()->merge($this->friendsOf());
//    }
    public function friends()
    {
        return Relations::where(function ($q) {
            $q->where('user_id', Auth::id())->orWhere('to_user_id', Auth::id());
        })->where(['type' => 'friend', 'status' => 'accept']);
    }

    public function friendsAndRequest()
    {
        return Relations::where(function ($q) {
            $q->where('user_id', Auth::id())->orWhere('to_user_id', Auth::id());
        })->where(['type' => 'friend']);
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function userJob()
    {
        return $this->belongsTo('App\Models\UserJob', 'user_job_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\UsersAddress', 'user_id');
    }

    public function default_address()
    {
        return $this->hasOne('App\Models\UsersAddress', 'user_id')
            ->where('default_address', 'yes');
    }

    public function categories()
    {
        if (!empty($this->interisted_categories)) {
            return ItemCategory::whereIn('id', explode(',', $this->interisted_categories));
        }else {
            return (object)[];
        }
    }

    public function interested_categories_data()//abdo
    {
        if (!empty($this->interisted_categories)) {
            return ItemCategory::whereIn('id', explode(',', $this->interisted_categories))->get();
        }else {
            return (object)[];
        }
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }


    public function PwdReset()
    {
        return $this->hasOne('App\Models\PwdReset', 'email', 'email');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\ItemLikes', 'item_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Item', 'user_id');
    }

    public function dealOut()
    {
        return $this->hasMany('App\Models\Deal', 'user_id');
    }

    public function dealIn()
    {
        return $this->hasMany('App\Models\Deal', 'item_owner_id');
    }

    public function stages()
    {
        return $this->hasMany('App\Models\Stage', 'user_id');
    }

    public function select_attribute()
    {
        return $this->morphMany('App\Models\SelectedAttributeValues', 'model', 'model_type', 'model_id');
    }

    public function sentEmails()
    {
        return $this->hasMany(Email::class, 'from_id');
    }

    public function receivedEmails()
    {
        return $this->hasMany(Email::class, 'to_id');
    }

    public function ranks(){

        $ranks =  Rank::where(['model_id'=>Auth::id(),'model_type'=>'App\Models\User'])->get();
        if(!empty($ranks)){
            return $ranks;
        }else{
            return (object)[];
        }
    }

}
