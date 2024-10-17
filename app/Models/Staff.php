<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\SystemTicket;
use Spatie\Activitylog\Traits\LogsActivity;

class Staff extends Authenticatable
{

    protected $table = 'staff';
    public $timestamps = true;
    use SoftDeletes;
    use Notifiable,LogsActivity,HasApiTokens;

    public $modelPath = 'App\Models\Staff';
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'avatar',
        'gender',
        'birthdate',
        'address',
        'password',
        'remember_token',
        'description',
        'job_title',
        'status',
        'permission_group_id',
    ];
    protected $hidden = array('password', 'remember_token');

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'national_id',
        'email',
        'mobile',
        'password',
        'job_title',
        'status',
        'permission_group_id',
    ];


    public static function StaffPerms($staffID){
        return Staff::find($staffID)->permission->pluck('route_name');
    }

    public function getFullnameAttribute($key)
    {
        if(isset($this->firstname) && strlen($this->firstname))
            $name = $this->firstname;
        if(isset($this->middlename) && strlen($this->middlename))
            $name .= ' ' .$this->middlename;

        if(isset($this->lastname) && strlen($this->lastname))
            $name .= ' ' .$this->lastname;

        return $name;
    }


    public function permission_group(){
        return $this->belongsTo('App\Models\PermissionGroup','permission_group_id');
    }

    public function permission(){
        return $this->hasManyThrough('App\Models\Permission','App\Models\PermissionGroup','id','permission_group_id','permission_group_id');
    }



    // --------------- CHAT

    public function chat_conversation_seen($ID){
        $result = $this->morphOne('App\Models\ChatConversationSeen','model')
            ->where('chat_conversation_id',$ID)
            ->whereRaw("(SELECT `id` FROM `chat_messages` WHERE `chat_messages`.`chat_conversation_id` = `chat_conversation_seen`.`chat_conversation_id` ORDER BY `id` DESC LIMIT 1) = `last_chat_message_id`")
            ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }



    public function chat_socket_access(){
        return $this->morphMany('App\Models\ChatSocketAccess','model');
    }

    public function chat_conversation_from(){
        return $this->morphMany('App\Models\ChatConversation','from');
    }

    public function chat_conversation_to(){
        return $this->morphMany('App\Models\ChatConversation','to');
    }

    public function conversation_seen(){
        return $this->morphMany('App\Models\ChatConversationSeen','model');
    }





    public function activity_log(){
        return $this->morphMany('Spatie\Activitylog\Models\Activity','causer');
    }



    public function findForPassport($username){
        return $this->where('email', $username)->first();
    }


}