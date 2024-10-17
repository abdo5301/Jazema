<?php

namespace App\Modules\Api\User;

use Auth;
use App;
use App\Models\Service;
use App\Models\User;
use App\Models\Item;
use App\Models\Page;
use App\Modules\Api\Transformers\User\ItemTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MiscApiController extends UserApiController
{

    public function about(){

        $about = Page::select('id',  'content_' . \DataLanguage::get() . ' as content')->find(1);

        $service = Service::select('id', 'name_' . \DataLanguage::get() . ' as name', 'description_' . \DataLanguage::get() . ' as description','image')
            ->orderBy('id','desc')->get();

        $items = Item   ::select('id') ->with('upload')->limit(10)->orderBy('rank','desc')->get();

        $users = User::select('id','firstname','lastname','image')->limit(10)->orderBy('rank','desc')->get();

        $itemTransformer = new ItemTransformer();
        $items = $itemTransformer->transformCollection($items->toArray(), [\DataLanguage::get()], 'relatesItems');

        $data['items'] = $items;
        $data['users'] = $users;
        $data['service'] = $service;
        $data['about'] = $about->content;

        return $this->respondSuccess($data, __('About Data'));

    }


    public function page(Request $request){

        $RequestData = $request->only(['id']);

        $validator = Validator::make($RequestData, [
            'id'=> 'required|exists:pages,id',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $page = Page::select('id',  'name_'. \DataLanguage::get() .' as name','content_' . \DataLanguage::get() . ' as content','image')
            ->findOrFail($RequestData['id']);


         return $this->respondSuccess($page,__('DATA'));

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