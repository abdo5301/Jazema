<?php

namespace App\Modules\Api\User;

use App\Models\Attribute;
use App\Models\Item;
use Auth;
use App;
use App\Modules\Api\Transformers\User\UserTransformer;
use function foo\func;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DealApiController extends UserApiController
{

    public function create(Request $request){


        if(!isset($request->create_deal)){
            return ['status'=>false,'msg'=>__('invaild Request')];
        }

        $data['item_id'] = $request->create_deal['item_id'];
        if(isset($request->create_deal['options'])){
            foreach ($request->create_deal['options'] as $row){
                $data['options'][$row['id']] = $row['value'];
            }

        }
        $create = new App\Libs\Create();
       return $create->Deal($data);



    }

    public function dealOut(Request $request){

        $data = Auth::user()->dealOut()->with(['user','owner','owner','options.item_option','options.item_option_values','item'=>function($q){
            $q->with('upload')->select('*','name_'.\DataLanguage::get().' as name')->get();
        }]);

        if($request->id){
            $data->where('id',$request->id);
        }

        if($request->user_id){
            $data->where('user_id',$request->user_id);
        }

        if($request->owner_id){
            $data->where('item_owner_id',$request->owner_id);
        }

        if($request->item_id){
            $data->where('item_id',$request->item_id);
        }


        $data = $data->orderBy('id','DESC')->jsonPaginate();

        if (!$data->items())
            return $this->respondNotFound(false, __('There Are no Deals to display'));

        $userTransfare = new UserTransformer();


        return $this->respondSuccess($userTransfare->transformCollection($data->toArray(),[\DataLanguage::get()],'deal'), __('Deals Out  Data'));

    }

    public function dealIn(Request $request){

        $data = Auth::user()->dealIn()->with(['user','owner','options.item_option','options.item_option_values','item'=>function($q){
            $q->with('upload')->select('*','name_'.\DataLanguage::get().' as name')->get();
        }]);
        if($request->id){
            $data->where('id',$request->id);
        }

        if($request->user_id){
            $data->where('user_id',$request->user_id);
        }

        if($request->owner_id){
            $data->where('item_owner_id',$request->owner_id);
        }

        if($request->item_id){
            $data->where('item_id',$request->item_id);
        }


        $data = $data->orderBy('id','DESC')->jsonPaginate();

        if (!$data->items())
            return $this->respondNotFound(false, __('There Are no Deals to display'));

        $userTransfare = new UserTransformer();


        return $this->respondSuccess($userTransfare->transformCollection($data->toArray(),[\DataLanguage::get()],'deal'), __('Deals In  Data'));

    }



    public function changeStatus(Request $request){

        $RequestData = $request->only(['id','status']);
        $validator = Validator::make($RequestData, [
            'id'          =>  'required|exists:deals,id',
            'status'      =>  'required|in:pending,inprogress,stopping,pause,done,cancel',
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }



        $deal = Auth::user()->dealIn()->find($RequestData['id']);

        if(empty($deal)){
            return $this->respondNotFound(false,__('Deal Not found'));
        }

        if($deal->status == 'done'){
            return $this->respondNotFound(false,__('Cannot change Deal has been Done'));
        }

        if($deal->update(['status'=>$RequestData['status']])){
            return $this->respondSuccess([], __('Status Changed'));
        }

        return $this->respondWithError(false,__('Error While Change Status'));



    }

    function rank(Request $request){
        $RequestData = $request->only(['id','rank']);
        $validator = Validator::make($RequestData, [
            'id'          =>  'required|exists:deals,id',
            'rank'      =>  'required|numeric',
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        


    }


}
