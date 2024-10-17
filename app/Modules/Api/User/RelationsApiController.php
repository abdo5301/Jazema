<?php

namespace App\Modules\Api\User;


use App;
use App\Models\Relations;
use App\Modules\Api\Transformers\User\UserTransformer;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class RelationsApiController extends UserApiController
{

    public function add(Request $request)
    {

        $RequestData = $request->only(['user_id', 'type']);
        $validator = Validator::make($RequestData, [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:friend,follow',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        if(Auth::id() == $RequestData['user_id']){
            return $this->respondWithError(false, __('Cannot Make Relation with Your Self'));
        }
        $user = Auth::user();

        if ($RequestData['type'] == 'follow') {
            $check = $user->following()->where('to_user_id', $RequestData['user_id'])->first();

            if (empty($check)) {
                if (Relations::create([
                    'to_user_id' => $RequestData['user_id'],
                    'type' => $RequestData['type'],
                    'user_id' => Auth::id(),
                    'status' => 'accept',
                ])) {
                    return $this->respondSuccess([], __('Following'));
                } else {
                    return $this->respondWithError(false, __('Cannot Follow Now'));
                }
            }else{
                return $this->respondWithError(false, __('You are Already Following'));
            }
        } else {
            $check = $user->friendsAndRequest()->where(function ($q) use ($RequestData) {
                $q->where('to_user_id', $RequestData['user_id'])->orWhere('user_id', $RequestData['user_id']);
            })->first();
            
            if (empty($check)) {
                if (Relations::create([
                    'to_user_id' => $RequestData['user_id'],
                    'type' => $RequestData['type'],
                    'user_id' => Auth::id(),
                    'status' => 'pending',
                ])) {
                    return $this->respondSuccess([], __('Request Sent'));
                } else {
                    return $this->respondWithError(false, __('Cannot Send Request Now'));
                }
            }else{
                if($check->status == 'pending'){
                    return $this->respondWithError(false, __('You Request Already Send'));
                } else {
                    return $this->respondWithError(false, __('You are Already Friend'));
                }
            }


        }


    }

    public function remove(Request $request)
    {

        $RequestData = $request->only(['id','type']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:relations,id',
            'type' => 'required|in:follow,friend',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $relation = Relations::where('type',$RequestData['type'])->find($RequestData['id']);
        if (empty($relation)) {
            return $this->respondWithError(false, __('Cannot Find Relation'));
        }

        if($relation->user_id != Auth::id()  && $relation->to_user_id != Auth::id() ){
            return $this->respondWithError(false, __('Wrong Relation'));
        }


        $relation->delete();
        return $this->respondSuccess([], __('Relation Removed'));


    }

    public function removeRelation(Request $request)
    {

        $RequestData = $request->only(['user_id','type']);
        $validator = Validator::make($RequestData, [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:follow,friend',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }





        if ($RequestData['type'] == 'follow') {
            $relation = Relations::where(['user_id'=>Auth::id(),'to_user_id'=> $RequestData['user_id'],'type'=> 'follow'])->first();
        } else {
            $relation = Relations::where(function ($q) use ($RequestData) {
                $q->where(['user_id' => Auth::id(), 'to_user_id'=> $RequestData['user_id']]);
            })->orWhere(function ($q) use ($RequestData) {
                $q->where(['to_user_id' => Auth::id(), 'user_id'=> $RequestData['user_id']]);
            })->first();
        }

        $relation->delete();
        return $this->respondSuccess([], __('Relation Removed'));


    }


    public function changeStatus(Request $request)
    {


        $RequestData = $request->only(['id', 'status']);
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:relations,id',
            'status' => 'required|in:accept,cancel',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $friend_request = Relations::where(['to_user_id'=>Auth::id(),'type'=>'friend'])->find($RequestData['id']);
        if(empty($friend_request)){
            return $this->respondWithError(false, __('Cannot find Request Now'));
        }
        if ($friend_request->status != 'pending') {
            return $this->respondWithError(false, __('Cannot find Pending Request Now'));
        }
        if ($RequestData['status'] == 'cancel') {
            $friend_request->update(['status' => 'cancel']);
            $friend_request->delete();
            return $this->respondSuccess([], __('Request Canceled'));
        } else {
            $friend_request->update(['status' => 'accept']);
            return $this->respondSuccess([], __('Request Accepted'));
        }


    }

    public function friendRequests()
    {

        $requests = Relations::where('to_user_id', Auth::id())->where('status', 'pending')->with('user.userJob');
        $rows = $requests->jsonPaginate();

        if (!$rows->items())
            return $this->respondNotFound(false, __('There Are no Requests to display'));

        $userTransformer = new UserTransformer();

        return $this->respondSuccess($userTransformer->transformCollection($rows->toArray(), [\DataLanguage::get()],'friendRequests'), __('Requests Data'));


    }

    public function followers()
    {

        $followers = Auth::user()->followers()->with(['user.userJob', 'to_user.userJob'])->jsonPaginate();

        if (!$followers->items())
            return $this->respondNotFound(false, __('There Are no Followers to display'));

        $userTransformer = new UserTransformer();

        return $this->respondSuccess($userTransformer->transformCollection($followers->toArray(), [\DataLanguage::get()],'followers'), __('followers Data'));
    }

    public function following()
    {

        $followers = Auth::user()->following()->with(['user.userJob', 'to_user.userJob'])->jsonPaginate();

        if (!$followers->items())
            return $this->respondNotFound(false, __('There Are no Followers to display'));

        $userTransformer = new UserTransformer();

        return $this->respondSuccess($userTransformer->transformCollection($followers->toArray(), [\DataLanguage::get()],'following'), __('followers Data'));
    }

    public function friends()
    {
        $friends = Auth::user()->friends()->with(['user.userJob','to_user.userJob'])->jsonPaginate();

        if (!$friends->items())
            return $this->respondNotFound(false, __('There Are no Friends to display'));

        $userTransformer = new UserTransformer();
        return $this->respondSuccess($userTransformer->transformCollection($friends->toArray(), [\DataLanguage::get()],'friends'), __('Friends Data'));
    }


}