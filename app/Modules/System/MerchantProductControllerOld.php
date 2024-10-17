<?php

namespace App\Modules\System;

use App\Http\Requests\MerchantProductCategoryFormRequest;
use App\Libs\DataLanguage;
use App\Models\MerchantProductAttributeValue;
use App\Models\MerchantProductCategory;
use App\Models\MerchantProductOption;
use App\Models\MerchantProductOptionsValues;
use App\Models\MerchantProductOptionValue;
use App\Models\MerchantProductPrices;
use App\Models\ProductAttribute;
use App\Models\Tax;
use Elasticsearch\Endpoints\Indices\Aliases\Update;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantProductFormRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\MerchantProducts;
use App\Models\MerchantProduct;
use Illuminate\Http\Request;
use App\Models\AreaType;
use App\Models\MerchantCategory;
use App\Models\MerchantProductTemplateOption;
use App\Models\MerchantProductTemplateAttribute;
use App\Models\MerchantProductAttribute;
use Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Merchant;
use App\Models\Upload;

class MerchantProductController extends SystemController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Merchant'),
                'url' => url('system/merchant')
            ]
        ];
    }


    public function index(Request $request){

        if($request->isDataTable){

            $eloquentData = MerchantProduct::viewData(\DataLanguage::get());
//            dd($eloquentData->get()->toArray());
            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            // Product Filter

            whereBetween($eloquentData,'DATE(merchant_products.created_at)',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_products.id', '=',$request->id);
            }


            if($request->merchant_id){
                $eloquentData->where('merchant_products.merchant_id',$request->merchant_id);

                if($request->merchant_product_category_id){
                    $eloquentData->where('merchant_products.merchant_product_category_id',$request->merchant_product_category_id);
                }

                if($request->created_by_merchant_staff_id){
                    $eloquentData->where('merchant_products.created_by_merchant_staff_id',$request->created_by_merchant_staff_id);
                }

            }

            if($request->name){
                orWhereByLang($eloquentData,'merchant_products.name',$request->name);
            }

            if($request->description){
                orWhereByLang($eloquentData,'merchant_products.description',$request->description);
            }

            whereBetween($eloquentData,'merchant_products.price',$request->price1,$request->price2);

            if($request->approved_by_staff_id){
                $eloquentData->where('merchant_products.approved_by_staff_id',$request->approved_by_staff_id);
            }


            if($request->is_approved == 'yes'){
                $eloquentData->whereNotNull('merchant_products.approved_at');
            }elseif($request->is_approved == 'no'){
                $eloquentData->whereNull('merchant_products.approved_at');
            }

            whereBetween($eloquentData,'merchant_products.approved_at',$request->approved_at1,$request->approved_at2);

            // Branch Filter
            if(is_array($request->area_id) && !empty($request->area_id) && !(count($request->area_id) == 1 && $request->area_id[0] == '0') ){
                $eloquentData->where('merchant_branches.area_id','IN',\App\Libs\AreasData::getAreasDown($request->area_id));
            }

            if($request->merchant_category_id){
                $eloquentData->where('merchants.merchant_category_id', '=',$request->merchant_category_id);
            }



            if($request->downloadExcel == "true") {
                if (staffCan('download.merchant-product.excel')) {
                    $excelData = $eloquentData;
                    $excelData = $excelData->get();

                    exportXLS(__('Merchant Product'),
                        [
                            __('ID'),
                            __('Merchant ID'),
                            __('Product Name'),
                            __('Merchant Name'),
                            __('Approved By Staff'),
                            __('Price'),
                            __('Status'),
                            __('Description'),
                            __('Approved At'),
                            __('Created At'),
                        ],
                        $excelData,
                        [
                            'id'                        => 'id',
                            'merchant_id'               =>'merchant_id',
                            'name'                      =>'name',
                            'merchant_name'             =>function($data){
                                return $data->merchant_name;
                            },
                            'staff_name'                 =>function($data){
                                return $data->approved_by_staff_name;
                            },
                            'price'                      =>'price',
                            'status'                     =>'status',
                            'description'                =>'description',
                            'approved_at'                =>function($data){
                                return $data->approved_at;
                            },
                            'created_at'                 =>function($data){
                                return $data->created_at->format('Y-m-d h:i A');
                            },
                        ]
                    );
                }
            }



            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name', function($data){
                    return $data->name;
                })
                ->addColumn('quantity','{{$quantity}}')
                ->addColumn('price','{{number_format($price)}}')
                ->addColumn('merchant_id',function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant->id).'">'.$data->merchant->{'name_'.\DataLanguage::get()}.' ('.$data->merchant->merchant_category->{'name_'.\DataLanguage::get()}.') '.'</a>';
                })
                ->addColumn('created_by',function($data){
                    return adminDefineUserWithName($data->creatable_type,$data->creatable_id,\DataLanguage::get());
                })

                ->addColumn('status',function($data){

                    if($data->approved_at){
                        return '<b style="color: green;">'.$data->approved_at.' Approved By ('.$data->approved->Fullname.')</b>';
                    }else{
                        return '<b style="color: red;"> In-Active </b>';
                    }
                })
                ->addColumn('create_at',function ($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.product.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.product.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('Quantity'),
                __('Price'),
                __('Merchant'),
                __('Created By'),
                __('Status'),
                __('Created At'),
                __('Action')];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Products');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Products');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Products'),
            ];
            $MerchantCategory = MerchantCategory::get(['id','name_'.\DataLanguage::get().' as name']);
            if($MerchantCategory->isNotEmpty()){
                $this->viewData['merchantCategories'] = array_merge(['Select Category'],array_column($MerchantCategory->toArray(),'name','id'));
            }else{
                $this->viewData['merchantCategories'] = [__('Select Category')];
            }

            return $this->view('merchant.product.index',$this->viewData);
        }
    }


    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Merchant Products'),
            'url' => url('system/merchant/product')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Merchant Products'),
        ];

        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if ($merchantID) {
            $merchantData = Merchant::findOrFail($merchantID);
            $this->viewData['merchantData'] = $merchantData;
        }

        // -- Category
        $this->viewData['MerchantProductCategory'] = ['Select Product Category'];
        $old_merchant_id = old('merchant_id');
        if ($old_merchant_id) {
            $merchantData = Merchant::find($old_merchant_id);
            $MerchantProductCategory = array_column(MerchantProductCategory::where('merchant_category_id', $merchantData->merchant_category_id)->get()->toArray(), 'name_' . \DataLanguage::get(), 'id');
            $this->viewData['MerchantProductCategory'] = array_merge($this->viewData['MerchantProductCategory'], $MerchantProductCategory);
            $this->viewData['current_merchant'] = Merchant::find($old_merchant_id);
            $this->viewData['current_template_option'] = array_column(MerchantProductTemplateOption::select('id', 'name_' . \DataLanguage::get() . ' as name')->where('merchant_id', $old_merchant_id)->get()->toArray(), 'name', 'id');
            if (old('merchant_product_category_id'))
                $this->viewData['current_attribute'] = ProductAttribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('merchant_product_category_id', old('merchant_product_category_id'))->with(['values' => function ($q) {
                    $q->select(['*', 'name_' . \DataLanguage::get() . ' as name']);
                }])->get();

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
            $this->viewData['old_tax_ids'] = [];
        }

        $this->viewData['tax'] = Tax::select('id', 'name_' . \DataLanguage::get() . ' as name')->get()->toArray();
        $this->viewData['pageTitle'] = __('Create Merchant Products');
        return $this->view('merchant.product.create', $this->viewData);
    }


    public function store(MerchantProductFormRequest $request)
    {
        $theRequest = $request->all();

        $productData = [
            'merchant_id' => $theRequest['merchant_id'],
            'status' => $theRequest['status'],
            'price' => $theRequest['price'],

            'merchant_product_category_id' => $theRequest['merchant_product_category_id'],
            'name_ar' => $theRequest['name_ar'],
            'name_en' => $theRequest['name_en'],
            'description_ar' => $theRequest['description_ar'],
            'description_en' => $theRequest['description_en'],
            'creatable_type' => 'App\Models\Staff',
            'creatable_id' => Auth::id(),


        ];
        if(isset($theRequest['tax_ids']))
            $productData ['tax_ids'] =   implode(',', $theRequest['tax_ids']);
        if ($request->status == 'active') {
            //  $theRequest['approved_by_staff_id'] = Auth::id();
            $productData['approved_at'] = Carbon::now();
            $productData['approved_by_staff_id'] = Auth::id();
        }

        if ($insertData = MerchantProduct::create($productData)) {


            // update images
            if ($request->temp_id) {
                Upload::where('temp_id', $request->temp_id)->update([
                    'model_type' => 'App\Models\MerchantProduct',
                    'model_id' => $insertData->id,
                    'temp_id' => ''
                ]);
            }


            if (!empty($theRequest['option'])) {
                foreach ($theRequest['option'] as $option) {
                    if ($option['template_option'] == 'new' && !empty($option['template_option'])) {   // new option

                        $newOption = [
                            'merchant_product_id' => $insertData->id,
                            'name_ar' => $option['option_name_ar'],
                            'name_en' => $option['option_name_en'],
                            'min_select' => $option['option_min_select'],
                            'max_select' => $option['option_max_select'],
                            'is_required' => $option['option_is_required'],
                            'type' => $option['option_type'],
                            'sort' => $option['option_sort'],
                            'status' => 'active',
                        ];
                        $optionData = MerchantProductOption::create($newOption);

                        $newOptionValue = [];
                        if ($option['option_type'] == 'select'
                            || $option['option_type'] == 'radio'
                            || $option['option_type'] == 'check'
                        ) {
                            if (!empty($option['option_value_name_ar'])) {
                                foreach ($option['option_value_name_ar'] as $key => $value) {
                                    $newOptionValue = [
                                        'merchant_product_option_id' => $optionData['id'],
                                        'name_ar' => $option['option_value_name_ar'][$key],
                                        'name_en' => $option['option_value_name_en'][$key],
                                        'price_prefix' => $option['option_value_price_prefix'][$key],
                                        'price' => $option['option_value_price'][$key],
                                    ];
                                    $optionValueData = MerchantProductOptionValue::create($newOptionValue);
                                }
                            }
                        }



                    }
                    if ($option['template_option'] != 'new' && !empty($option['template_option'])) {      // template option

                        $templateOption = MerchantProductTemplateOption::find($option['template_option']);
                        if ($templateOption) {

                            $newOption = [
                                'merchant_product_id' => $insertData->id,
                                'name_ar' => $templateOption->name_ar,
                                'name_en' => $templateOption->name_en,
                                'min_select' => $templateOption->min_select,
                                'max_select' => $templateOption->max_select,
                                'is_required' => $templateOption->is_required,
                                'type' => $templateOption->type,
                                'sort' => $option['option_sort'],
                                'status' => 'active',
                            ];
                            $newOptionValue = [];
                            if ($templateOption->type == 'select'
                                || $templateOption->type == 'radio'
                                || $templateOption->type == 'check'
                            ) {

                                if (!empty($templateOption->template_option_values)) {
                                    foreach ($templateOption->template_option_values as $value) {
                                        $newOptionValue[] = [
                                            'merchant_product_option_id' => $optionData->id,
                                            'name_ar' => $value->name_ar,
                                            'name_en' => $value->name_en,
                                            'price_prefix' => $value->price_prefix,
                                            'price' => $value->price,
                                            'status' => 'active',
                                        ];

                                    }
                                }
                            }
                            $newOption['values'] = $newOptionValue;
                            $optionData = MerchantProductOption::create($newOption);
                        }

                    }

                }


            }


            if (!empty($theRequest['attribute'])) {
                $attributes = ProductAttribute::where('merchant_product_category_id', $theRequest['merchant_product_category_id'])->get();
                foreach ($attributes as $key => $value) {
                    $newAttribute = [
                        'merchant_product_id' => $insertData->id,
                        'product_attribute_id' => $value->id,
                    ];
                    if ($value->type == 'text' || $value->type == 'textarea') {
                        $newAttribute['product_attribute_value_id'] = 0;
                        $newAttribute['value'] = $theRequest['attribute'][$value->id];
                        MerchantProductAttributeValue::create($newAttribute);
                    }
                    if ($value->type == 'select') {
                        $newAttribute['product_attribute_value_id'] = $theRequest['attribute'][$value->id];
                        $newAttribute['value'] = '';
                        MerchantProductAttributeValue::create($newAttribute);

                    }

                    if ($value->type == 'multi-select') {
                        if(!empty($theRequest['attribute'][$value->id])){
                            foreach ($theRequest['attribute'][$value->id] as $row) {
                                $newAttribute['product_attribute_value_id'] = $row;
                                $newAttribute['value'] = '';
                                MerchantProductAttributeValue::create($newAttribute);
                            }
                        }}
                }
            }


            return redirect()
                ->route('merchant.product.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()
                ->route('merchant.product.create')
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t add Merchant Products'));
        }
    }

    public function show(MerchantProduct $product)
    {
//        dd($product->attribute()->get()->toArray());
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Product'),
            'url'=> route('merchant.product.index')
        ];
        $this->viewData['breadcrumb'][] = [
            'text'=> $product->{'name_'.\DataLanguage::get()},
        ];
        $this->viewData['pageTitle'] = __('Merchant Product');

        $this->viewData['result'] = $product;
        $this->viewData['taxes'] = Tax::whereIn('id',explode(',',$product->tax_ids))->get();
        return $this->view('merchant.product.show',$this->viewData);
    }


    public function edit(MerchantProduct $product)
    {
        $this->viewData['breadcrumb'][] = [
            'text' => __('Merchant Product'),
            'url' => url('system/merchant/product')
        ];
        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Merchant Product'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Product');

        $this->viewData['result'] = $product;

        $this->viewData['MerchantProductCategory'] = ['Select Product Category'];
        $MerchantProductCategory = array_column(MerchantProductCategory::where('merchant_category_id', $product->merchant->merchant_category_id)->get()->toArray(), 'name_' . \DataLanguage::get(), 'id');
        $this->viewData['MerchantProductCategory'] = array_merge($this->viewData['MerchantProductCategory'], $MerchantProductCategory);

        $this->viewData['current_template_option'] = array_column(MerchantProductTemplateOption::select('id', 'name_' . \DataLanguage::get() . ' as name')->where('merchant_id', $product['merchant_id'])->get()->toArray(), 'name', 'id');
        $this->viewData['current_attribute'] = ProductAttribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('merchant_product_category_id', $product->merchant_product_category_id)->with(['values' => function ($q) {
            $q->select(['*', 'name_' . \DataLanguage::get() . ' as name']);
        }])->get();

        if(!empty($this->viewData['current_attribute'])){
            foreach ($this->viewData['current_attribute'] as $key => $attribute ){
                if($attribute->type == 'text' || $attribute->type == 'textarea'){

                    $selectedAttribute = MerchantProductAttributeValue::where('product_attribute_id', $attribute->id)
                        ->where('merchant_product_id',$product->id)->first();
                    $this->viewData['current_attribute'][$key]['value'] = $selectedAttribute->value;

                }elseif ($attribute->type == 'select'){
                    $selectedAttribute = MerchantProductAttributeValue::where('product_attribute_id', $attribute->id)
                        ->where('merchant_product_id',$product->id)->first();
                    $this->viewData['current_attribute'][$key]['value'] = $selectedAttribute->product_attribute_value_id;
                }
                elseif($attribute->type == 'multi-select') {
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
            $this->viewData['old_tax_ids'] = explode(',',$product->tax_ids);
        }

        $this->viewData['tax'] =  Tax::select('id', 'name_' . \DataLanguage::get() . ' as name')->get()->toArray();


        return $this->view('merchant.product.create', $this->viewData);
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

    public function update(MerchantProductFormRequest $request, MerchantProduct $product)
    {
        $theRequest = $request->all();

        $productData = [
            'merchant_id' => $theRequest['merchant_id'],
            'status' => $theRequest['status'],
            'price' => $theRequest['price'],
            'tax_ids' => implode(',',$theRequest['tax_ids']),
            'merchant_product_category_id' => $theRequest['merchant_product_category_id'],
            'name_ar' => $theRequest['name_ar'],
            'name_en' => $theRequest['name_en'],
            'description_ar' => $theRequest['description_ar'],
            'description_en' => $theRequest['description_en'],
            'description_en' => $theRequest['description_en'],
            'creatable_type' => 'App\Models\Staff',
            'creatable_id' => Auth::id(),
        ];
        if ($product->update($productData)) {



            if ($request->temp_id) {
                Upload::where('temp_id', $request->temp_id)->update([
                    'model_type' => 'App\Models\MerchantProduct',
                    'model_id' => $product->id,
                    'temp_id' => ''
                ]);
            }



            if (!empty($theRequest['option'])) {
                foreach ($theRequest['option'] as $key => $option) {

                    if(isset($option['id'])){
                        $currentOption = [
                            'merchant_product_id' => $product->id,
                            'name_ar' => $option['option_name_ar'],
                            'name_en' => $option['option_name_en'],
                            'min_select' => $option['option_min_select'],
                            'max_select' => $option['option_max_select'],
                            'is_required' => $option['option_is_required'],
                            'type' => $option['option_type'],
                            'sort' => $option['option_sort'],
                        ];

                        $optionData = MerchantProductOption::where('id',$option['id'])->update($currentOption);
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
                            if(isset($option['option_value_id'][$key]))
                                $optionValue = MerchantProductOptionValue::where('id',$option['option_value_id'][$key])->update($currentOptionValue);
                            else
                                $optionValue = MerchantProductOptionValue::create($currentOptionValue);
                        }

                    }else {
                        if ($option['template_option'] == 'new' && !empty($option['template_option'])) {   // new option

                            $newOption = [
                                'merchant_product_id' => $product->id,
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

                        } if ($option['template_option'] != 'new' && !empty($option['template_option'])) {      // template option

                            $templateOption = MerchantProductTemplateOption::find($option['template_option']);
                            if ($templateOption) {

                                $newOption = [
                                    'merchant_product_id' => $product->id,
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

                MerchantProductAttributeValue::where('merchant_product_id',$product->id)->delete();
                $attributes = ProductAttribute::where('merchant_product_category_id', $product->merchant_product_category_id)->get();

                foreach ($attributes as $key=> $value ){


                    $optionValueMultiSelect = [
                        'merchant_product_id' => $product->id,
                        'product_attribute_id' => $value->id,
                    ];

                    if ($value->type == 'text' || $value->type == 'textarea') {

                        $optionValueMultiSelect['value'] = $theRequest['attribute'][$value->id];

                        $insert =   MerchantProductAttributeValue::create($optionValueMultiSelect);


                    } elseif ($value->type == 'select') {

                        $optionValueMultiSelect['product_attribute_value_id'] = $theRequest['attribute'][$value->id];

                        $insert =   MerchantProductAttributeValue::create($optionValueMultiSelect);

                    } elseif ($value->type == 'multi-select') {

                        foreach ($theRequest['attribute'][$value->id] as $oneValue){
                            $optionValueMultiSelect['product_attribute_value_id'] = $oneValue;

                            $insert =   MerchantProductAttributeValue::create($optionValueMultiSelect);

                        }


                    }


                }}




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

    public function destroy(MerchantProduct $product, Request $request)
    {
        $product->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Product has been deleted successfully')];
        } else {
            redirect()
                ->route('merchant.product.index')
                ->with('status', 'success')
                ->with('msg', __('This product has been deleted'));
        }
    }


}