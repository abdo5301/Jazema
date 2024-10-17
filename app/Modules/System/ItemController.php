<?php

namespace App\Modules\System;

use App\Libs\Create;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemTypes;
use App\Models\Product;
use App\Models\TemplateOption;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantProductFormRequest;
use Illuminate\Http\Request;
use Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Merchant;
use App\Models\Upload;

class ItemController extends SystemController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],

        ];
    }


    public function index(Request $request)
    {
        if ($request->isDataTable) {

            $eloquentData = Item::select([
                'id',
                'item_category_id',
                'item_type_id',
                'user_id',
                'name_'.\DataLanguage::get() .' as name',
                'description_'.\DataLanguage::get().' as description',
                'price',
                'quantity',
                'status',
                'created_at',
                'staff_id'
            ]);
            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }


            // Item Filter

            whereBetween($eloquentData, 'DATE(items.created_at)', $request->created_at1, $request->created_at2);

            if ($request->id) {
                $eloquentData->where('id', '=', $request->id);
            }


            if ($request->user_id) {
                $eloquentData->where('user_id', $request->user_id);
            }

                if ($request->item_category_id) {
                    $eloquentData->where('item_category_id', $request->item_category_id);
                }

                if ($request->item_type_id) {
                    $eloquentData->where('item_type_id', $request->item_type_id);
                }

            if ($request->name) {
                $eloquentData->where('name_ar', 'LIKE','%'.$request->name.'%')
                ->orWhere('name_en'.'LIKE','%'.$request->name.'%');
            }

            whereBetween($eloquentData, 'price', $request->price1, $request->price2);

            if ($request->staff_id) {
                $eloquentData->where('staff_id', $request->staff_id);
            }
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('user_id',function($data){
                    return '<a target="_blank" href="'.route('system.users.show',$data->user->id).'">'.$data->user->Fullname.'</a>';

                })
                ->addColumn('item_category_id',function($data){
                    return '<a target="_blank" href="'.route('system.item_category.show',$data->item_category->id).'">'.$data->item_category->{'name_'.\DataLanguage::get()}.'</a>';
                })
                ->addColumn('item_type_id',function($data){
                    return '<a target="_blank" href="'.route('system.item_type.show',$data->item_type->id).'">'.$data->item_type->{'name_'.\DataLanguage::get()}.'</a>';
                })
                ->addColumn('quantity', '{{$quantity}}')
                ->addColumn('price', function($data){
                    return amount($data->price,true);
                })
//                ->addColumn('staff_id', function ($data) {
//                    return '<a target="_blank" href="' . route('system.staff.show', $data->staff->id) . '">' . $data->staff->Fullname. '</a>';
//                })
                ->addColumn('created_by', function ($data) {
                    if($data->creatable_id)
                    return adminDefineUserWithName($data->creatable_type, $data->creatable_id, \DataLanguage::get());
                    return '--';
                })
                ->addColumn('status', function ($data) {

                    if ($data->status == 'active') {
                        return '<b style="color: green;">' .__('Active').'</b>';
                    } else {
                        return '<b style="color: red;">'. __('In-Active'). '</b>';
                    }
                })
                ->addColumn('created_at', function ($data) {
                    if($data->created_at)
                    return $data->created_at->diffForHumans();
                    return '--';
                })
                ->addColumn('action', function ($data) {

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.show', $data->id) . "\">" . __('View') . "</a></li>
                            
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        } else {

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('User'),
                __('Item Category'),
                __('Item Type'),
                __('Quantity'),
                __('Price'),
                __('Created By'),
                __('Status'),
                __('Created At'),
                __('Action')];

            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Items');
            } else {
                $this->viewData['pageTitle'] = __('Items');
            }

            $this->viewData['breadcrumb'][] = [
                'text' => __('Items'),
            ];
            $MerchantCategory = ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name']);
            if ($MerchantCategory->isNotEmpty()) {
                $this->viewData['merchantCategories'] = array_merge(['Select Category'], array_column($MerchantCategory->toArray(), 'name', 'id'));
            } else {
                $this->viewData['merchantCategories'] = [__('Select Category')];
            }

            return $this->view('item.index', $this->viewData);
        }
    }


    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Items'),
            'url' => route('system.item.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Item'),
        ];

   //Category
        $this->viewData['ItemCategory'] = ['Select Item Category'];
        $ItemCategory = array_column(ItemCategory::get()->toArray(), 'name_' . \DataLanguage::get(), 'id');
        $this->viewData['ItemCategory'] = array_merge($this->viewData['ItemCategory'], $ItemCategory);

//Type
        $this->viewData['ItemType'] = ['Select Item Type'];
        $itemType = array_column(ItemTypes::get()->toArray(), 'name_' . \DataLanguage::get(), 'id');
        $this->viewData['ItemType'] = array_merge($this->viewData['ItemType'],$itemType);


        if (!empty(old('temp_id'))) {
            $this->viewData['temp_id'] = old('temp_id');
            $this->viewData['temp_image'] = Upload::where('temp_id', old('temp_id'))->get();
        } else {
            $this->viewData['temp_id'] = md5(uniqid() . time() . rand() . rand(1, 999999));
        }

        $this->viewData['pageTitle'] = __('Create Item');
        return $this->view('item.create', $this->viewData);
    }


    public function store(Request $request)
    {
        $theRequest = $request->all();
       // $theRequest['staff_id'] = Auth::id();
       
        $create = new Create();

     return $create->Item($theRequest,\DataLanguage::get());

    }

    public function show(Item $item,Request $request)
    {
//        dd($item->upload);
//dd($item->likes->toArray());
        $this->viewData['breadcrumb'][] = [
            'text' => __('Item'),
            'url' => route('system.item.index')
        ];

        if ($request->isRank) {

            $eloquentData = $item->ranks();
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('user_id',function($data){
                    return '<a target="_blank" href="'.route('system.users.show',$data->user->id).'">'.$data->user->Fullname.'</a>';

                })
                ->addColumn('comment',function($data){
                    return '<code>'.str_limit($data->comment,25).'</code>';
                })
                ->addColumn('created_at', function ($data) {
                    if($data->created_at)
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->make(true);
        }
        if ($request->isComment) {

            $eloquentData = $item->comment();
//            dd($eloquentData->get()->toArray());
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('user_id',function($data){
                    if (!empty($data->user->id)) {
                        return '<a target="_blank" href="' . route('system.users.show', $data->user->id) . '">' . $data->user->Fullname . '</a>';
                    }else{
                        return '--';
                    }

                })
                ->addColumn('comment',function($data){
                    return '<code>'.str_limit($data->comment,25).'</code>';
                })
                ->addColumn('status', function ($data) {

                    if ($data->status == 'active') {
                        return '<b style="color: green;">' .__('Active').'</b>';
                    } else {
                        return '<b style="color: red;">'. __('In-Active'). '</b>';
                    }
                })
                ->addColumn('created_at', function ($data) {
                    if($data->created_at)
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->make(true);
        }
        if ($request->isLike) {

            $eloquentData = $item->likes();
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('user_id',function($data){
                    return '<a target="_blank" href="'.route('system.users.show',$data->user->id).'">'.$data->user->Fullname.'</a>';

                })
                ->addColumn('created_at', function ($data) {
                    if($data->created_at)
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->make(true);
        }



        $this->viewData['breadcrumb'][] = [
            'text' => $item->{'name_' . \DataLanguage::get()},
        ];
        $this->viewData['pageTitle'] = __('Show Item');

        $this->viewData['result'] = $item;

        return $this->view('item.show', $this->viewData);
    }


    public function edit(Item $item)
    {
        $this->viewData['breadcrumb'][] = [
            'text' => __('Merchant Product'),
            'url' => url('system/merchant/product')
        ];
        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Merchant Product'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Product');

        $this->viewData['result'] = $item;

   //     $this->viewData['MerchantProductCategory'] = ['Select Product Category'];
//        $MerchantProductCategory = array_column(MerchantProductCategory::where('merchant_category_id', $product->merchant->merchant_category_id)->get()->toArray(), 'name_' . \DataLanguage::get(), 'id');
 //       $this->viewData['MerchantProductCategory'] = array_merge($this->viewData['MerchantProductCategory'], $MerchantProductCategory);

        $this->viewData['current_template_option'] = array_column(MerchantProductTemplateOption::select('id', 'name_' . \DataLanguage::get() . ' as name')->where('merchant_id', $product['merchant_id'])->get()->toArray(), 'name', 'id');
        $this->viewData['current_attribute'] = ProductAttribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('merchant_product_category_id', $product->merchant_product_category_id)->with(['values' => function ($q) {
            $q->select(['*', 'name_' . \DataLanguage::get() . ' as name']);
        }])->get();

        if (!empty($this->viewData['current_attribute'])) {
            foreach ($this->viewData['current_attribute'] as $key => $attribute) {
                if ($attribute->type == 'text' || $attribute->type == 'textarea') {

                    $selectedAttribute = AttributeValues::where('attribute_id', $attribute->id)
                        ->where('merchant_product_id', $product->id)->first();
                    $this->viewData['current_attribute'][$key]['value'] = $selectedAttribute->value;

                } elseif ($attribute->type == 'select') {
                    $selectedAttribute = MerchantProductAttributeValue::where('product_attribute_id', $attribute->id)
                        ->where('merchant_product_id', $product->id)->first();
                    $this->viewData['current_attribute'][$key]['value'] = $selectedAttribute->product_attribute_value_id;
                } elseif ($attribute->type == 'multi-select') {
                    $selectedAttribute = MerchantProductAttributeValue::where('product_attribute_id', $attribute->id)
                        ->selectRaw('*, GROUP_CONCAT(`product_attribute_value_id`) as mulit_values')
                        ->first();
                    $this->viewData['current_attribute'][$key]['value'] = $selectedAttribute->mulit_values;
                }

            }
        }
        if (!empty(old('temp_id'))) {
            $this->viewData['temp_id'] = old('temp_id');
            $this->viewData['temp_image'] = Upload::where('temp_id', old('temp_id'))->get();
        } else {
            $this->viewData['temp_id'] = md5(uniqid() . time() . rand() . rand(1, 999999));
        }


        if (!empty(old('tax_ids'))) {
            $this->viewData['old_tax_ids'] = array_map('intval', old('tax_ids'));
        } else {
            $this->viewData['old_tax_ids'] = explode(',', $product->tax_ids);
        }

        $this->viewData['tax'] = Tax::select('id', 'name_' . \DataLanguage::get() . ' as name')->get()->toArray();


        return $this->view('item.create', $this->viewData);
    }

    public function upload_image(Request $request)
    {


        $validator = Validator::make($request->toArray(), [
            'uploadfile' => 'image|required',

        ]);

        if ($validator->errors()->all()) {
            return array('success' => false, 'msg' => $validator->errors()->all());
        }

        $file = $request->uploadfile;

        $data = [
            'path' => $file->store('product'),
            'title' => '',
            'temp_id' => $request->temp_id
        ];
        $upload = Upload::create($data);

        if ($upload) {
            $path = asset('storage/' . $data['path']);
            return array('success' => true, 'data' => ['title' => $request->image_title, 'path' => $path, 'image_id' => $upload->id]);
        } else {
            return array('success' => false, 'msg' => 'ERROR');
        }
    }

    function remove_image(Request $request)
    {
        $image = Upload::where('id', $request->id)->first();
        if ($image->delete()) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    public function update(MerchantProductFormRequest $request, Item $item)
    {
        $theRequest = $request->all();

        $productData = [
            'merchant_id' => $theRequest['merchant_id'],
            'status' => $theRequest['status'],
            'price' => $theRequest['price'],
            'tax_ids' => implode(',', $theRequest['tax_ids']),
            'merchant_product_category_id' => $theRequest['merchant_product_category_id'],
            'name_ar' => $theRequest['name_ar'],
            'name_en' => $theRequest['name_en'],
            'description_ar' => $theRequest['description_ar'],
            'description_en' => $theRequest['description_en'],
            'creatable_type' => 'App\Models\Staff',
            'creatable_id' => Auth::id(),
        ];
        if ($item->update($productData)) {


            if ($request->temp_id) {
                Upload::where('temp_id', $request->temp_id)->update([
                    'model_type' => 'App\Models\MerchantProduct',
                    'model_id' => $item->id,
                    'temp_id' => ''
                ]);
            }


            if (!empty($theRequest['option'])) {
                foreach ($theRequest['option'] as $key => $option) {

                    if (isset($option['id'])) {
                        $currentOption = [
                            'merchant_product_id' => $item->id,
                            'name_ar' => $option['option_name_ar'],
                            'name_en' => $option['option_name_en'],
                            'min_select' => $option['option_min_select'],
                            'max_select' => $option['option_max_select'],
                            'is_required' => $option['option_is_required'],
                            'type' => $option['option_type'],
                            'sort' => $option['option_sort'],
                        ];

                        $optionData = MerchantProductOption::where('id', $option['id'])->update($currentOption);
                        if ($option['option_type'] == 'select'
                            || $option['option_type'] == 'radio'
                            || $option['option_type'] == 'check'
                        ) {

                            if (!empty($option['option_value_name_ar'])) {
                                foreach ($option['option_value_name_ar'] as $key => $value) {
                                    $currentOptionValue = [
                                        'name_ar' => $option['option_value_name_ar'][$key],
                                        'name_en' => $option['option_value_name_en'][$key],
                                        'price_prefix' => $option['option_value_price_prefix'][$key],
                                        'price' => $option['option_value_price'][$key],
                                        'merchant_product_option_id' => $option['id'],
                                    ];
                                }
                            }
                            if (isset($option['option_value_id'][$key]))
                                $optionValue = MerchantProductOptionValue::where('id', $option['option_value_id'][$key])->update($currentOptionValue);
                            else
                                $optionValue = MerchantProductOptionValue::create($currentOptionValue);
                        }

                    } else {
                        if ($option['template_option'] == 'new' && !empty($option['template_option'])) {   // new option

                            $newOption = [
                                'merchant_product_id' => $item->id,
                                'name_ar' => $option['option_name_ar'],
                                'name_en' => $option['option_name_en'],
                                'min_select' => $option['option_min_select'],
                                'max_select' => $option['option_max_select'],
                                'is_required' => $option['option_is_required'],
                                'type' => $option['option_type'],
                                'sort' => $option['option_sort'],
                            ];


                            $optionData = MerchantProductOption::create($newOption);
                            if ($option['option_type'] == 'select'
                                || $option['option_type'] == 'radio'
                                || $option['option_type'] == 'check'
                            ) {

                                if (!empty($option['option_value_name_ar'])) {
                                    foreach ($option['option_value_name_ar'] as $key => $value) {
                                        $newOptionValue = [
                                            'merchant_product_option_id' => $optionData->id,
                                            'name_ar' => $option['option_value_name_ar'][$key],
                                            'name_en' => $option['option_value_name_en'][$key],
                                            'price_prefix' => $option['option_value_price_prefix'][$key],
                                            'price' => $option['option_value_price'][$key],
                                        ];
                                    }
                                }
                                $optionValue = MerchantProductOptionValue::create($newOptionValue);

                            }

                        }
                        if ($option['template_option'] != 'new' && !empty($option['template_option'])) {      // template option

                            $templateOption = MerchantProductTemplateOption::find($option['template_option']);
                            if ($templateOption) {

                                $newOption = [
                                    'merchant_product_id' => $item->id,
                                    'name_ar' => $templateOption->name_ar,
                                    'name_en' => $templateOption->name_en,
                                    'min_select' => $templateOption->min_select,
                                    'max_select' => $templateOption->max_select,
                                    'is_required' => $templateOption->is_required,
                                    'type' => $templateOption->type,
                                ];

                                $optionData = MerchantProductOption::create($newOption);

                                $newOptionValue = [];
                                if ($templateOption->type == 'select'
                                    || $templateOption->type == 'radio'
                                    || $templateOption->type == 'check'
                                ) {

                                    if (!empty($templateOption->template_option_values)) {
                                        foreach ($templateOption->template_option_values as $value) {
                                            $newOptionValue = [
                                                'merchant_product_option_id' => $optionData->id,
                                                'name_ar' => $value->name_ar,
                                                'name_en' => $value->name_en,
                                                'price_prefix' => $value->price_prefix,
                                                'price' => $value->price,
                                            ];

                                        }
                                    }

                                    $optionValue = MerchantProductOptionValue::create($newOptionValue);

                                }


                            }


                        }
                    }
                }


            }


            if (!empty($theRequest['attribute'])) {

                MerchantProductAttributeValue::where('merchant_product_id', $product->id)->delete();
                $attributes = ProductAttribute::where('merchant_product_category_id', $product->merchant_product_category_id)->get();

                foreach ($attributes as $key => $value) {


                    $optionValueMultiSelect = [
                        'merchant_product_id' => $product->id,
                        'product_attribute_id' => $value->id,
                    ];

                    if ($value->type == 'text' || $value->type == 'textarea') {

                        $optionValueMultiSelect['value'] = $theRequest['attribute'][$value->id];

                        $insert = MerchantProductAttributeValue::create($optionValueMultiSelect);


                    } elseif ($value->type == 'select') {

                        $optionValueMultiSelect['product_attribute_value_id'] = $theRequest['attribute'][$value->id];

                        $insert = MerchantProductAttributeValue::create($optionValueMultiSelect);

                    } elseif ($value->type == 'multi-select') {

                        foreach ($theRequest['attribute'][$value->id] as $oneValue) {
                            $optionValueMultiSelect['product_attribute_value_id'] = $oneValue;

                            $insert = MerchantProductAttributeValue::create($optionValueMultiSelect);

                        }


                    }


                }
            }


            return redirect()
                ->route('merchant.product.edit', $product->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully edited Merchant Product'));
        } else {
            return redirect()
                ->route('merchant.product.edit')
                ->with('status', 'success')
                ->with('msg', __('Sorry Couldn\'t Edit Merchant Product'));;
        }
    }

    public function destroy(Item $item, Request $request)
    {

        $item->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Product has been deleted successfully')];
        } else {
            redirect()
                ->route('system.item.index')
                ->with('status', 'success')
                ->with('msg', __('This product has been deleted'));
        }
    }
     public function merchantOptions(Request $request){
        $template_option =  TemplateOption::select('id','name_'.\DataLanguage::get().' as name');
        if($request->user_id)
            $template_option->where('user_id',$request->user_id);
         if($request->category_id)
             $template_option->Where('item_category_id',$request->category_id);
            $template_option = $template_option->get()->toArray();
       // dd($template_option);
        $return = '<option value="template" >'.__('Select Option').'</option>';
        $return .= '<option value="new" >'.__('New Option').'</option>';
        if(!empty($template_option)){
            foreach ($template_option as $key=>$row){
                $return .= '<option value="'.$row['id'].'" >'.$row['name'].'</option>';
            }
        }
        return $return;
    }
    function getAttributes(Request $request){
        if(!$request->category_id && !$request->item_type_id)
            return ['status'=>false,'msg'=>__('please select category')];
        $attribute = Attribute::select(['*','name_'.\DataLanguage::get().' as name'])->orderBy('sort')
            ->where(['model_id'=>$request->category_id,'model_type'=>'App\Models\ItemCategory','item_type_id'=>$request->item_type_id])
            ->with(['values'=>function($q){
            $q->select(['*','name_'.\DataLanguage::get().' as name'])->get();
        }])->get();
//        dd($attribute->toArray());
        if(empty($attribute->toArray()))
            return ['status'=>false];

        return ['status'=>true,'data'=>$attribute];
    }

    public function getItemTypes(Request $request){
        $asd = explode("\n", setting('item_type_ids'));
        if (in_array((int)$request->item_type_id,$asd)){

            return ['status'=>true,'data'=>''];
        }else{
            return ['status'=>false,'data'=>''];
        }
    }

}