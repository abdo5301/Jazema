<?php

namespace App\Modules\Api\User;

use Auth;
use App;
use App\Models\Email;
use App\Models\Item;
use App\Modules\Api\Transformers\User\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class EmailApiController extends UserApiController
{


    public function compose(Request $request){

        $RequestData = $request->only(['subject','message','item_id']);
        $validator = Validator::make($RequestData, [
            'item_id'=> 'required|exists:items,id',
            'message'=> 'required',
            'subject'=> 'nullable',
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $item = Item::find($RequestData['item_id']);

        $create = Email::create([
            'from_id'=>Auth::id(),
            'item_id'=>$RequestData['item_id'],
            'to_id'=>$item->user_id,
            'subject'=>$RequestData['subject'],
            'message'=>$RequestData['message'],
        ]);

            if($create){
                return $this->respondSuccess([],__('Email Sent'));
            }else{
                return $this->respondNotFound([], __('Cannot Send Email Now,Please try again later'));
            }

    }

    function inbox(Request $request){


        $email = Email::whereNull('deleted_to')->where('to_id',Auth::id())->with('from_user','item');

        if($request->word){
            $email->where(function ($q) use ($request){
                $q->where('subject','like','%'.$request->word.'%')->orWhere('message','like','%'.$request->word.'%');
            });
        }
        $email = $email->orderBy('id','DESC')->with('item')->jsonPaginate();

        if (!$email->items())
            return $this->respondNotFound([], __('There Are no Emails to display'));

        $userTransformer = new UserTransformer();

        return $this->respondSuccess($userTransformer->transformCollection($email->toArray(), [\DataLanguage::get()], 'inboxEmails'), __('Inbox'));


    }

    function sent(Request $request){


        $email = Email::whereNull('deleted_from')->where('from_id',Auth::id())->with('to_user','item');

        if($request->word){
            $email->where(function ($q) use ($request){
                $q->where('subject','like','%'.$request->word.'%')->orWhere('message','like','%'.$request->word.'%');
            });
        }
        $email = $email->orderBy('id','DESC')->with('item')->jsonPaginate();

        if (!$email->items())
            return $this->respondNotFound([], __('There Are no Emails to display'));

        $userTransformer = new UserTransformer();

        return $this->respondSuccess($userTransformer->transformCollection($email->toArray(), [\DataLanguage::get()], 'sentEmails'), __('Inbox'));


    }

     function to_trash(Request $request){
         $RequestData = $request->only(['id','type']);
         $validator = Validator::make($RequestData, [
             'id'=> 'required|exists:emails,id',
             'type'=> 'required|in:inbox,sent',
         ]);
         if ($validator->errors()->any()) {
             return $this->ValidationError($validator, __('Validation Error'));
         }

         $email =  Email::where('id',$RequestData['id']);

         if($RequestData['type'] == 'inbox'){
             $email->where('to_id',Auth::id())->whereNull('deleted_to');
         }else{
             $email->where('from_id',Auth::id())->whereNull('deleted_from');
         }

         $email = $email->first();

         if(empty($email))
             return $this->respondNotFound([], __('There Are no Emails to Trash'));

         if($RequestData['type'] == 'inbox'){
             $update = $email->update(['deleted_to'=>date('Y-m-d H:i:s')]);
         }else{
             $update = $email->update(['deleted_from'=>date('Y-m-d H:i:s')]);
         }

         if($update){
             return $this->respondSuccess([],__('Email Trashed'));
         }else{
             return $this->respondNotFound([], __('Cannot Trash Email Now,Please try again later'));
         }

     }


     function trash(Request $request){

         $email = Email::with(['from_user','to_user','item'])->where(function ($q){
             $q->whereNotNull('deleted_from')->where('from_id',Auth::id());
         })->orWhere(function ($q){
             $q->whereNotNull('deleted_to')->where('to_id',Auth::id());
         });

         if($request->word){
             $email->where('subject','like','%'.$request->word.'%')->orWhere('content','like','%'.$request->word.'%');
         }

         $email = $email->orderBy('id','DESC')->with('item')->jsonPaginate();

         if (!$email->items())
             return $this->respondNotFound([], __('There Are no Emails to display'));

         $userTransformer = new UserTransformer();

         return $this->respondSuccess($userTransformer->transformCollection($email->toArray(), [\DataLanguage::get()], 'trashEmails'), __('Inbox'));


     }

     function return_form_trash(Request $request){

         $RequestData = $request->only(['id']);
         $validator = Validator::make($RequestData, [
             'id'=> 'required|exists:emails,id',
         ]);
         if ($validator->errors()->any()) {
             return $this->ValidationError($validator, __('Validation Error'));
         }

         $email = Email::where(function ($q){
             $q->whereNotNull('deleted_from')->where('from_id',Auth::id());
         })->orWhere(function ($q){
             $q->whereNotNull('deleted_to')->where('to_id',Auth::id());
         })->find($RequestData['id']);


         if(empty($email))
             return $this->respondNotFound([], __('Email Not Found'));

         if($email->to_id == Auth::id()) {
             $update = $email->update(['deleted_to'=>null]);

             }
            if($email->from_id == Auth::id()) {
                $update = $email->update(['deleted_from'=>null]);
         }

         if($update){
             return $this->respondSuccess([],__('Email returned'));
         }else{
             return $this->respondNotFound([], __('Cannot return Email Now,Please try again later'));
         }


     }


     function delete(Request $request){

         $RequestData = $request->only(['id']);
         $validator = Validator::make($RequestData, [
             'id'=> 'required|exists:emails,id',
         ]);
         if ($validator->errors()->any()) {
             return $this->ValidationError($validator, __('Validation Error'));
         }

         $email = Email::where(function ($q){
             $q->whereNotNull('deleted_from')->where('from_id',Auth::id());
         })->orWhere(function ($q){
             $q->whereNotNull('deleted_to')->where('to_id',Auth::id());
         })->find($RequestData['id']);


         if(empty($email))
             return $this->respondNotFound([], __('Email Not Found'));

         $delete = $email->delete();

         if($delete){
             return $this->respondSuccess([],__('Email Deleted'));
         }else{
             return $this->respondNotFound([], __('Cannot delete Email Now,Please try again later'));
         }



     }



    public function ValidationError($validation, $message)
    {
        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function ($key, $val) {
            return ['key' => $key, 'val' => implode('|', $val)];
        }, array_keys($errorArray), $errorArray), 'val', 'key');

        return [
            'status' => false,
            'msg' => implode("\n", array_flatten($errorArray)),
            'data' => $data
        ];

    }


}