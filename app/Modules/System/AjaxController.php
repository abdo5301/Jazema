<?php

namespace App\Modules\System;

use App\Models\Upload;

use Illuminate\Http\Request;

use App\Models\Staff;
use App\Models\User;
use App\Models\Item;

use Carbon;

use App\Models\PermissionGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AjaxController extends SystemController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get(Request $request){

        switch ($request->type){

            case 'system_load_avg':
                if(function_exists('sys_getloadavg')){
                    return sys_getloadavg()[0];
                }

                return 0.5;

                break;



            case 'getProductCategory':
                $merchantID = $request->merchant_id;
                $Categories = Merchant::find($merchantID)->merchant_category->merchant_product_categories()->select('id','name_ar as name')->get()->toArray();
                //array_unshift($Categories,['id'=>'0','name'=>__('Select product category')]);
                return $Categories;
                break;
                
                
                case 'getProducts':
                $catID = (int) $request->category_id;
                $Products = MerchantProduct::select(['id',"merchant_products.name_".\DataLanguage::get()." as name",'price'])
                    ->where('merchant_product_category_id',$catID)->get()->toArray();
                array_unshift($Products,['id'=>'0','name'=>__('Select product')]);
                return $Products;

            break;

            case 'MerchantProduct':
                $merchantID = (int) $request->merchant_id;
                $Products = MerchantProduct::select(['id',"merchant_products.name_".\DataLanguage::get()." as name"])
                    ->where('merchant_id',$merchantID)->where('status','active')->get()->toArray();
                array_unshift($Products,['id'=>'0','name'=>__('Select product')]);
                return $Products;

                break;

            case 'staff_by_permission_groups':
                $id = $request->group_id;
                return Staff::where('permission_group_id',$id)->select(['id','firstname','lastname'])->get()->toArray();
                break;
                

            case 'getMerchantStaff':
                $merchantID = $request->merchant_id;

                return MerchantStaff::viewData(\DataLanguage::get())
                    ->where('merchant_staff_groups.merchant_id',$merchantID)->get();

                break;

            case 'user':
                $word = $request->word;
                $data = User::where('id','=',$word)
                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),'LIKE','%'.$word.'%')
                    ->orwhere('email','LIKE','%'.$word.'%')
                    ->orwhere('mobile','LIKE','%'.$word.'%')
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;


            case 'UserItem':
                $merchantID = (int) $request->item_owner_id;
                $items = Item::select(['id',"items.name_".\DataLanguage::get()." as name"])
                    ->where('user_id',$merchantID)->where('status','active')->get()->toArray();
                array_unshift($items,['id'=>'0','name'=>__('Select Item')]);
                return $items;

                break;


            case 'getNextAreas':
                return \App\Libs\AreasData::getNextAreas($request->id,\DataLanguage::get());
                break;

            case 'staff':
                $word = $request->word;

                $data = Staff::whereRaw("CONCAT(firstname,' ',lastname) LIKE ('%$word%')")
                    ->orWhere('email','LIKE','%'.$word.'%')
                    ->orWhere('mobile','LIKE','%'.$word.'%')
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;

            case 'users':
                $word = $request->word;
                $data = User::whereRaw("CONCAT(firstname,' ',lastname) LIKE('%?%')",[$word])
                    ->orWhere('email','LIKE',"%$word%")
                    ->orWhere('mobile','LIKE',"%$word%")
                    ->orWhere('national_id','LIKE',"%$word%")
                    ->orWhere('id',$word)
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;

                break;
         case 'item':
                $word = $request->word;

                $data = Item::select(['id',"items.name_".\DataLanguage::get()." as name"])
                    ->where('name_ar','LIKE',"%$word%")
                    ->orWhere('name_en','LIKE',"%$word%")
                    ->get();

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id,
                    ];
                }

                return $return;

                break;

            case 'productAttributes':
                $proid = (int) $request->proid;
                $lang = \DataLanguage::get();
                $attributes = ProductAttribute::viewData(\DataLanguage::get(),[])->where('product_id','=',$proid)->get();

                $oldattributevalues = Attribute::whereIn('id',$attributes->pluck('attribute_id'))
                    ->with(['attributeValue'=>function($sqlQuery)use($lang){
                        $sqlQuery->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
                    }])->get();

                return $attributes->groupBy('attribute_id');

                break;

            case 'customer':
                $word = $request->word;
                if(strlen($word < 11))
                    return;
                $data = User::where('mobile','=',$word)
                    ->orwhere('mobile','LIKE','%'.$word.'%')
                    ->get(['id','mobile as value']);

                return $data;

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->mobile.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;
        }

    }


}
