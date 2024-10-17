<?php
namespace App\Modules\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserJob;
use App\Models\Attributes;
use App\Models\Stage;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\SelectedAttributeValues;
use App\Models\Attribute;
use App\Models\AttributeValues;
use function foo\func;
use Illuminate\Support\Facades\Hash;
use App\Modules\Api\Transformers\User\UserTransformer;
use App\Modules\Api\Transformers\User\ItemTransformer;
use Auth;
use App;
use App\Libs\Create;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller {
    public $systemLang;
    public $JsonData;
    public $StatusCode = 200;
    public $Code = 300;
    public $lastupdate;
    public $Date = '2017-11-12 12:11:11';


    public function __construct(){

        $this->middleware('auth:api')->except([
            'login','callback','sendResetLinkEmail','register','getDatabase','verifyReset','CheckRegister',
            'aboutUs','checkversion','checkUser','item_data','all_items','ItemDetails','job_attributes','jobs',
            'ItemComments','ItemTypes','item_attributes','about','search','profilePage','page','categoryTree','categoryTable'
        ]);
        $this->content = [];
        $this->systemLang = App::getLocale();
        $this->lastupdate = (object)[
            'Database'              => $this->Date,
            'Application'           => '1.5',
        ];
        $this->JsonData = request()->all();
        if ((isset($this->JsonData['lang'])) && (in_array($this->JsonData['lang'], ['ar', 'en']))) {
            App::setLocale($this->JsonData['lang']);
            $this->systemLang = $this->JsonData['lang'];
        } else {
            $this->systemLang = App::getLocale();
        }
//die(App::getLocale());
        if (empty($this->systemLang)) {
            App::setLocale('ar');
            $this->systemLang = 'ar';
        }

//        if(in_array(request()->lang,['ar','en']))
//            $this->systemLang = request()->lang;
//        else
//            $this->systemLang = 'en';


    }

    public function Wishlist(){
        $wishlist = Auth::user()->wishlist()->select('item_id')->with(['item'=>function($q){
            $q->select('id','name_ar');
        }])->get();
        if( $wishlist->isNotEmpty()){

            $Items = Item::Actives()
                ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
                ->with(['item_category' => function ($q) {
                    $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
                }, 'item_type' => function ($q) {
                    $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
                }, 'upload' , 'user', 'owner_user'])->whereIn('id',array_column($wishlist->toArray(),'item_id'));

            $Items->orderBy('id', 'desc');


            $rows = $Items->jsonPaginate();

            if (!$rows->items())
                return $this->respondNotFound(false, __('There Are no items to display'));

            $itemTransformer = new ItemTransformer();

            return $this->respondSuccess($itemTransformer->transformCollection($rows->toArray(), [\DataLanguage::get()]), __('Items Data'));


            return $this->respondSuccess($wishlist,__('Wishlist'));
        }else{
            return $this->respondWithError([],__('No Wishlist to view'));
        }
    }

    public function AddToWishlist(Request $request){
        $RequestData = $request->only(['item_id']);
        $validator = Validator::make($RequestData, [
            'item_id'          =>  'required|exists:items,id'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        $check = Auth::user()->wishlist()->where(['item_id'=>$RequestData['item_id']])->first();
        if(!empty($check)){
            return $this->respondWithError([],__('item is already added'));
        }

        if(Auth::user()->wishlist()->create(['item_id'=>$RequestData['item_id']])){
            return $this->respondWithoutError([],__('Item Added to Wishlist'));
        }else{
            return $this->respondWithError([],__('Cannot add Item to wishlist'));
        }
    }

    public function DeleteFromWishlist(Request $request){
        $RequestData = $request->only(['item_id']);
        $validator = Validator::make($RequestData, [
            'item_id'          =>  'required|exists:items,id'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        $check = Auth::user()->wishlist()->where(['item_id'=>$RequestData['item_id']])->first();
        if(empty($check)){
            return $this->respondWithError([],__('item Not Exists'));
        }else{
            if($check->delete()){
                return $this->respondWithoutError([],__('Item Deleted from Wishlist'));
            }else{
                return $this->respondWithError([],__('Cannot Delete Item from wishlist'));
            }
        }
    }

    public function myProfile(){

        $user = Auth::user();
        $deals_out = count($user['dealOut']->toArray());
        $deals_in = count($user['dealIn']->toArray());
        $user->deals_out = $deals_out;
        $user->deals_in = $deals_in;

        $user->count_ranks = count($user->ranks());
        $transformer = new UserTransformer();
        $user['stages'] = $user->stages;
        $user['userJob'] = $user->userJob;
        return $this->respondWithoutError($transformer->Profile(['user'=>$user,'attributes'=>$user->select_attribute()->with(['values','Attribute.values'])->get()],[\DataLanguage::get()]),__('User Data'));


    }



public function profile(Request $request){

    $RequestData = $request->only(['id']);
    $validator = Validator::make($RequestData, [
        'id'          =>  'required|exists:users,id',
    ]);

    if($validator->errors()->any()){
        return $this->ValidationError($validator,__('Validation Error'));
    }

    $user = User::with(['dealOut','dealIn','userJob','stages'])->find($request->id);

    $deals_out = count($user['dealOut']->toArray());
    $deals_in = count($user['dealIn']->toArray());
    $user->deals_out = $deals_out;
    $user->deals_in = $deals_in;
    $user->count_ranks = count($user->ranks());
    $following = '';
    $friend = '';
   // if(Auth::check()){
        $following_ids = Auth::user()->following()->select('to_user_id')->get();
         if(!empty($following_ids)){
         if(in_array($RequestData['id'],array_column($following_ids->toArray(),'to_user_id'))){
             $following = 'yes';

         }
        }

        $friends_ids = Auth::user()->friends()->select('to_user_id','user_id')->get();
        if(!empty($friends_ids)){
            if( in_array($RequestData['id'],array_column($friends_ids->toArray(),'user_id')) ||
                in_array($RequestData['id'],array_column($friends_ids->toArray(),'to_user_id')) ){
                $friend = 'yes';
            }
        }

   // }

    $user->following = $following;
    $user->friend = $friend;


    $user->increment('views');
    $transformer = new UserTransformer();
    return $this->respondWithoutError($transformer->Profile(['user'=>$user,'attributes'=>$user->select_attribute()->with(['values','Attribute.values'])->get()],[\DataLanguage::get()]),__('User Data'));

}



    public function myProfilePage(Request $request){



        $user = User::find(Auth::id());

        $stages = Stage::select('id','name')->where('user_id',Auth::id());

        $items = $user->items()
            ->where('status','active')
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'upload' => function ($q) {
                $q->first();
            }, 'user', 'owner_user']);
        if(empty($request->stage_id)){
            $public_stage = clone $stages;
            $public_stage_id = $public_stage->where('show_to_public','yes')->first()->id;
            $items->where('stage_id',$public_stage_id);
        }else{
            $public_stage_id = $request->stage_id;
        }
        $items->where('stage_id',$public_stage_id);
        $items =  $items->jsonPaginate();
        $stages = $stages->get()->toArray();
        if (!$items->items()){
            $items = [];}
        else {
            $itemTransformer = new ItemTransformer();
            $items = $itemTransformer->transformCollection($items->toArray(), [\DataLanguage::get()]);

        }

        return $this->respondWithoutError(['items'=>$items,'stages'=>$stages],__('Profile Page'));

    }


public function profilePage(Request $request){


    $RequestData = $request->only(['id']);
    $validator = Validator::make($RequestData, [
        'id'          =>  'required|exists:users,id',
    ]);

    if($validator->errors()->any()){
        return $this->ValidationError($validator,__('Validation Error'));
    }

    $user = User::find($request->id);

    $stages = Stage::select('id','name')->where('user_id',$request->id);

        $items = $user->items()
            ->where('status','active')
            ->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description', 'created_at'])
            ->with(['item_category' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'item_type' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name', 'slug_' . \DataLanguage::get() . ' as slug']);
            }, 'upload' => function ($q) {
                $q->first();
            }, 'user', 'owner_user']);
        if(empty($request->stage_id)){
            $public_stage = clone $stages;
            $public_stage_id = $public_stage->where('show_to_public','yes')->first()->id;
            $items->where('stage_id',$public_stage_id);
        }else{
            $public_stage_id = $request->stage_id;
        }
        $items->where('stage_id',$public_stage_id);
    $items =  $items->jsonPaginate();
    $stages = $stages->get()->toArray();
        if (!$items->items()){
            $items = [];}
else {
    $itemTransformer = new ItemTransformer();
    $items = $itemTransformer->transformCollection($items->toArray(), [\DataLanguage::get()]);

}

    return $this->respondWithoutError(['items'=>$items,'stages'=>$stages],__('Profile Page'));

}


    public function jobs(){
         return $this->respondWithoutError(UserJob::select('id','name_'.\DataLanguage::get().' as name')->get(),__('Job Attributes'));
    }




    public function job_attributes(Request $request){

        $RequestData = $request->only(['job_id']);
        $validator = Validator::make($RequestData, [
            'job_id'          =>  'required|exists:user_jobs,id',
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        $job = UserJob::find($request->job_id)->attributes()->select('id','type','name_'.\DataLanguage::get().' as name','is_required')
        ->orderBy('sort','asc')->with(['values'=>function($q){
            $q->select('id','attribute_id','name_'.\DataLanguage::get().' as name')->orderBy('sort','asc')->get();
            }])->get();

        return $this->respondWithoutError($job,__('Job Attributes'));

    }

    public function register(Request $request){

        $theRequest = $request->all();

        $data = [];
        if(isset($theRequest['sign_up'])){
            $data = $theRequest['sign_up'];
             
            if(isset($theRequest['sign_up']['job_attributes']) && !empty($theRequest['sign_up']['job_attributes'])){
                unset($data['job_attributes']);
                foreach ($theRequest['sign_up']['job_attributes'] as $row){
                  // $data['attribute['.$row["id"].']']= $row['value'];
                    $attribute = Attribute::find($row['id']);
                    if(empty($attribute)) {
                        return ['msg' =>__('Attribute '.$row['id'].' Not Found '),'status'=>false];
                    }else{
                        if($attribute->type == 'multi_select'){
                            $data['attribute['.$row["id"].']']= explode(',',$row['value']);
                        }else {
                            $data['attribute[' . $row["id"] . ']'] = $row['value'];
                        }
                    }
                }
            }
        }

        $create = new Create();

        return $create->User($data, \DataLanguage::get());

    }

    public function EditProfile(){

        $user = Auth::user();
        $interisted_categories = ItemCategory::select('id','name_ar as name')->whereIn('id', explode(',', $user->interisted_categories))->get();
        $transformer = new UserTransformer();
        $job_attribures =  $user->userJob->attributes;
        $selected_attributes = $user->select_attribute()->with(['values','Attribute.values'])->get();
        $selected_attributes_handled = [];

        foreach ($job_attribures as $row){
            $values = [];
            $value_id=0;
            $value = '';

            foreach ($selected_attributes as $attribute){
                if($row->id == $attribute->attribute_id){
                    if (!empty($attribute->attribute_value_id)) {
                        if($row->type == 'multi_select'){
                            $selected_values = SelectedAttributeValues::where(['attribute_id'=>$attribute->attribute_id,
                                'model_id'=>$user->id,'model_type'=>'App\Models\User'])
                                ->with('values')->get(['attribute_value_id']);
                            $value_id = array_column($selected_values->toArray(),'attribute_value_id');
                            $value = implode(',',array_column(AttributeValues::whereIn('id',$value_id)->get(['name_ar'])->toArray(),'name_ar'));
                            $value_id = implode(',',$value_id);
                        }else  {
                            $value_id = $attribute->attribute_value_id;
                            foreach ($attribute->attribute->values as $val ){
                                if($val->id == $attribute->attribute_value_id) {
                                    $value = $val->name_ar;
                                }
                            }
                        }
                    }else {
                        $value = $attribute['value'];
                    }
                }
            }
            if($row->values->isNotEmpty()) {
                $values = $transformer->transformCollection($row->values->toArray(), [\DataLanguage::get()], 'UserAttributeValues');
            }
            $selected_attributes_handled[]=[
                'id' => $row->id,
                'type' => $row->type,
                'is_required' => $row->is_required,
                'name' => $row->name_ar,
                'selected_value_name' => (!empty($value))? $value : '',
                'selected_value' => (!empty($value_id))? $value_id : '',
                'values' => $values,
            ];
        }
          return $this->respondWithoutError($transformer->userData(['user'=>$user,'interisted_categories'=>$interisted_categories,'stages'=>$user->stages,'attributes'=>$selected_attributes_handled],[\DataLanguage::get()]),__('User Data'));
    }



    public function EditProfileAction(Request $request){

        $theRequest = $request->all();
        $data = [];
        if(isset($theRequest['edit_profile'])){
            $data = $theRequest['edit_profile'];
            if(isset($theRequest['edit_profile']['job_attributes']) && !empty($theRequest['edit_profile']['job_attributes'])){
                unset($data['job_attributes']);
                foreach ($theRequest['edit_profile']['job_attributes'] as $row){
                    $attribute = Attribute::find($row['id']);
                    if(empty($attribute)) {
                        return ['msg' =>__('Attribute '.$row['id'].' Not Found '),'status'=>false];
                            }else{
                        if($attribute->type == 'multi_select'){
                            $data['attribute['.$row["id"].']']= explode(',',$row['value']);
                        }else {
                            $data['attribute[' . $row["id"] . ']'] = $row['value'];
                        }
                    }

                }
            }
        }

        $create = new Create();

        return $create->EditUserProfile($data, \DataLanguage::get());

    }

//    public function changePassword(Request $request){
//        $RequestData = $request->only(['old_password','new_password','password_confirmation']);
//        $validator = Validator::make($RequestData, [
//            'old_password'          =>  'required|string',
//            'new_password'          =>  'required|string|confirmed',
//
//        ]);
//
//        if($validator->errors()->any()){
//            return $this->ValidationError($validator,__('Validation Error'));
//        }
//
//        $user = Auth::user();
//        if(bcrypt($RequestData['old_password']) == $user->password){
//            $user->update(['password'=>$RequestData['new_password']]);
//            return $this->setCode(302)->respondWithOutError(true,__('Password Changed'));
//        }else{
//            return $this->respondWithError(false,__('Wrong Old password'));
//        }
//
//    }

    public function changePassword(Request $request){
        $userObj = Auth::user();

        $RequestData =$request->only(['password','password_confirmation','current_password']);
        $validator = Validator::make($RequestData, [
            'current_password'  => 'required|PasswordCheck:'.$userObj->password,
            'password'          => 'required|confirmed|min:6',
        ]);
        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        if(!$userObj->update(['password'=>Hash::make($RequestData['password'])]))
            return $this->respondNotFound(false,__('Could not change password'));
        return $this->respondSuccess(true,__('Password successfuly changed'));
    }

    public function actualLogin($username,$password){
        if(
            Auth('web')->attempt(['mobile' => $username, 'password' => $password,'status'=>'active'])
            || Auth('web')->attempt(['email' => $username, 'password' => $password,'status'=>'active'])
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUser(Request $request){
        $RequestData = $request->only(['username']);
        $validator = Validator::make($RequestData, [
            'username'          =>  'required',
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        $user = User::where('users.email','=',$RequestData['username'])
            ->orWhere('users.mobile', '=', $RequestData['username'])
            ->Active()
            ->first();

        if($user){
            return $this->respondWithoutError(true,__('User found'));
        } else {
            return $this->respondNotFound(false,__('User not found'));
        }
    }

    public function login(Request $request){
        $RequestData = $request->only(['username','password','rememberme']);
        $validator = Validator::make($RequestData, [
            'username'          =>  'required',
            'password'          =>  'required',
            //'rememberme'        =>  'required|in:0,1'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        //TODO Token lifetime

        if($this->actualLogin($RequestData['username'],$RequestData['password'])){
            $token = $this->GenerateToken($RequestData['username'],$RequestData['password']);

            if($token['status']){
                $UserInfoTransformer = new UserTransformer();
                $token['data']->user = $UserInfoTransformer->transform(['user'=>Auth::user(),'stages'=>Auth::user()->stages->toArray()]);
                return $this->respondWithoutError($token['data'],'Successfuly logged in');
            } else {
                return $this->setCode(302)->respondWithError(false,$token['msg']);
            }
        } else {
            return $this->setCode(302)->respondWithError(false,__('Wrong username OR password'));
        }
    }

    public function stages(){
        return $this->respondWithoutError(Auth::user()->stages()->select('id', 'name','show_to_friends','show_to_followers',
            'show_to_public')->get(), __('Data'));
    }

    public function createStage(Request $request){

        $RequestData = $request->only(['name','show_to_friends','show_to_followers','show_to_public']);
        $validator = Validator::make($RequestData, [
            'name'          =>  'required',
            'show_to_friends'          =>  'required|in:yes,no',
            'show_to_followers'          =>  'required|in:yes,no',
            'show_to_public'          =>  'required|in:yes,no'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        if(Auth::user()->stages()->create($RequestData)){
            return $this->respondWithoutError(Auth::user()->stages()->select('id', 'name','show_to_friends','show_to_followers',
                'show_to_public')->get(),__('Stage Added'));
        }else{
            return $this->respondWithError([],__('Cannot Add Stage Now,Please Try Again Later'));
        }
    }

    public function updateStage(Request $request){

        $RequestData = $request->only(['id','name','show_to_friends','show_to_followers','show_to_public']);
        $validator = Validator::make($RequestData, [
            'id'          =>  'required|exists:stages,id',
            'name'          =>  'required',
            'show_to_friends'          =>  'required|in:yes,no',
            'show_to_followers'          =>  'required|in:yes,no',
            'show_to_public'          =>  'required|in:yes,no'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        $stage = Auth::user()->stages()->where('id',$RequestData['id'])->first();
        if(empty($stage)){
            return $this->respondWithError([],__('There is no stage '));
        }
        unset($RequestData['id']);
        if($stage->update($RequestData)){
            return $this->respondWithoutError([],__('Stage Updated'));
        }else{
            return $this->respondWithError([],__('Cannot Update Stage Now,Please Try Again Later'));
        }
    }



    public function checkuserStatus($user=null){
        $userobj = (($user) ? $user : (Auth::user()) ? Auth::user() : null);
        if(isset($userobj) && ($userobj->status == 'in-active'))
           return $this->respondWithError(false,__('Deactivated Account'));
    }

    public function GenerateToken($username,$password){
        $client = new \GuzzleHttp\Client;
        try {
            $response = $client->post( 'http://jazemaa.amrbdr.com/oauth/token', [
                'form_params' => [
                    'client_id' => 1,
                    'client_secret' => 'Smc6wgld7gmf1Hvqf6HP4a9gr3SQh5OzhfVwAkTt',
                    'grant_type' => 'password',
                    'username' => $username,
                    'password' => $password,
                    'scope' => '*',
                ]
            ]);
            return ['status'=>true,'data'=>json_decode( (string) $response->getBody() )];
        } catch (RequestException $e){

            return ['status'=>true,'msg'=>__('Couldn\'t generate token, try again later')];
        }
    }


    public function verify(Request $request){
        $RequestData = $request->only(['code']);
        $validator = Validator::make($RequestData, [
            'code'    =>  'required|min:7|max:7'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        if(($user = Auth::user()) && (isset($RequestData['code']))){
            if($user->verification->code == $RequestData['code']){
                if($user->verify($RequestData['code']))
                    return $this->respondSuccess(false,__('Account Successfuly verified'));
                else
                    return $this->respondWithError(false,__('Could not verify account'));
            } else
                return $this->respondWithError(false,__('Wrong verification code'));
        }
        return $this->respondNotFound(false,__('No verification code provided'));
    }


    //TODO Test Purposes
    public function data(){
        dd(Auth()->user());
    }

    function no_access(){
        return ['status'=>false,'msg'=> __('You don\'t have permission to preform this action')];
    }



    function headerdata($keys){
        if(is_array($keys)) {
            $response = [];
            foreach ($keys as $key) {
                $response[$key] = array_key_exists($key,$this->JsonData) ? $this->JsonData[$key] : null;
            }
            request()->merge($response);
            return $response;
        } elseif (isset($keys)){
            $response = array_key_exists($keys,$this->JsonData)  ? $this->JsonData[$keys] : null;
            request()->merge($response);
            return $response;
        } else {
            request()->merge($this->JsonData);
            return $this->JsonData;
        }
    }


    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



    public function setStatusCode($StatusCode){
        $this->StatusCode = $StatusCode;
        return $this;
    }

    public function getStatusCode(){
        return $this->StatusCode;
    }

    public function setCode($code){
        $this->Code = $code;
        return $this;
    }

    public function getCode(){
        return $this->Code;
    }

    function ReturnMethod($condition,$truemsg,$falsemsg,$data=false){
        if($condition)
            return ['status'=>true,'msg'=>$truemsg,'data'=>$data];
        else
            return ['status'=>false,'msg'=>$falsemsg,'data'=>$data];
    }

    public function respondSuccess($data,$message = 'Success'){
        return $this->setStatusCode(200)->setCode(300)->respondWithoutError($data,$message);
    }

    public function respondCreated($data,$message = 'Row has been created'){
        return $this->setStatusCode(200)->setCode(300)->respondWithoutError($data,$message);
    }

    public function respondNotFound($data,$message = 'Not Found!'){
        return $this->setStatusCode(200)->setCode(301)->respondWithError($data,$message);
    }

    public function respond($data,$headers=[]){
        $data['version'] = $this->lastupdate;
        return response()->json($data,$this->getStatusCode(),$headers);
    }

    public function respondWithoutError($data,$message){
        if(is_array($data)){
            $data['version'] = $this->lastupdate;
        } else if(is_object($data)) {
            $data->version = $this->lastupdate;
        } else {
            $data = array_merge([$data],[
                'version'=> $this->lastupdate,
            ]);
        }
        return response()->json([
            'status' => true,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }

    public function respondWithError($data,$message){
        if(is_array($data)){
            $data['version'] = $this->lastupdate;
        } else if(is_object($data)) {
            $data->version = $this->lastupdate;
        } else {
            $data = array_merge([$data],[
                'version'=> $this->lastupdate,
            ]);
        }
        return response()->json([
            'status' => false,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }

    public function ValidationError($validation,$message){
        $errorArray = $validation->errors()->messages();

        $data['errors'] = array_column(array_map(function($key,$val) {
            return ['key'=>$key,'val'=>implode('|',$val)];
        },array_keys($errorArray),$errorArray),'val','key');

        $data['version'] = $this->lastupdate;

        return $this->setCode(303)->respondWithError($data,$message);
    }


}