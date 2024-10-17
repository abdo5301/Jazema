<?php

namespace App\Modules\System;

use App\Http\Requests\MerchantProductTemplateAttributeFormRequest;
use App\Http\Requests\MerchantProductTemplateOptionFormRequest;
use App\Http\Requests\TaxesFormRequest;
use App\Http\Requests\UserAddressesFormRequest;
use App\Http\Requests\UserRelativeRelationFormRequest;
use App\Http\Requests\UserRelativesFormRequest;
use App\Libs\Create;
use App\Models\AreaType;
//use App\Models\MerchantProductTemplateOption;
use App\Models\MerchantProductTemplateOption;
use App\Models\MerchantProductTemplateOptionValues;
use App\Models\Tax;
use App\Models\User;
use App\Models\UserRelativeRelation;
use App\Models\UserRelatives;
use App\Models\UsersAddress;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\UserFormRequest;
class MerchantProductTemplateOptionsController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = MerchantProductTemplateOption::select([
                'id',
                'name_'.\DataLanguage::get().' as name',
                'min_select',
                'max_select',
                'type',
                'is_required',
                'merchant_id'
            ]);


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'DATE(created_at)',$request->created_at1,$request->created_at2);
            whereBetween($eloquentData,'min_select',$request->min_select1,$request->min_select2);
            whereBetween($eloquentData,'max_select',$request->max_select1,$request->max_select2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->name){
                $eloquentData->where('name_ar', 'LIKE',$request->name)
                    ->orWhere('name_en','LIKE','%'.$request->name.'%');
            }

            if($request->type){
                $eloquentData->where('type', '=',$request->type);
            }
            if($request->is_required){
                $eloquentData->where('is_required', '=',$request->is_required);
            }
            if($request->merchant_id){
                $eloquentData->where('merchant_id', '=',$request->merchant_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('min_select','{{$min_select}}')
                ->addColumn('max_select','{{$max_select}}')
                ->addColumn('type','{{$type}}')
                ->addColumn('is_required','{{$is_required}}')

                ->addColumn('merchant_id',function ($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant->id).'">'.$data->merchant->{'name_'.\DataLanguage::get()}.'</a>';
                })

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.option.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.option.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.option.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>

                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Name',
                'Min Select',
                'Max select',
                'Type',
                'Is Required',
                'Merchant',
                'Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Product Template Options')
            ];
            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Product Template Options');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Product Template Options');
            }



            return $this->view('merchant-product-template-option.index',$this->viewData);
        }

    }

    public function create()
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product Template Option'),
            'url'=> route('system.option.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create  Product Template Option'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Product Template Option');
        return $this->view('merchant-product-template-option.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantProductTemplateOptionFormRequest $request)
    {
        $theRequest = $request->all();

        $theRequest['staff_id'] = Auth::id();
        $option = MerchantProductTemplateOption::create($theRequest);

        if ($option) {
            foreach ($request->option_value_name_ar as $key => $value) {
                $items['name_ar'] = $request->option_value_name_ar[$key];
                $items['name_en'] = $request->option_value_name_en[$key];
                $items['price_prefix'] = $request->option_value_price_prefix[$key];
                $items['price'] = $request->option_value_price[$key];
                $option->optionValues()->create($items);
            }
            return redirect()
                ->route('system.option.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        }else{
            return redirect()
                ->route('system.option.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Product Template Option'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantProductTemplateOption $option,Request $request){
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Product Template Option'),
                // 'url'=> route('system.address.index'),
                'url'=> route('system.option.index'),
            ],
            [
                'text'=> __('Show Product Template Option'),
            ]
        ];


        if($request->isDataTable){

            $eloquentData = MerchantProductTemplateOptionValues::select([
                'id',
                'name_'.\DataLanguage::get().' as name',
                'price_prefix',
                'price',
                'created_at'
            ])
                ->where('merchant_product_template_option_id',$option->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('price_prefix','{{$price_prefix}}')
                ->addColumn('price','{{$price}}')
                ->addColumn('created_at',function ($data){
                    return $data->created_at->diffForHumans();
                })


//                    ->addColumn('action',function($data){
//                        return " <div class=\"dropdown\">
//                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
//                              <span class=\"caret\"></span></button>
//                              <ul class=\"dropdown-menu\">
//                                <li class=\"dropdown-item\"><a href=\"".route('system.option.show',$data->id)."\">".__('View')."</a></li>
//                                <li class=\"dropdown-item\"><a href=\"".route('system.option.edit',$data->id)."\">".__('Edit')."</a></li>
//                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.option.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
//
//                              </ul>
//                            </div>";
//                    })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Name',
                'Price Prefix',
                'Price',
                'Created At'
            ];
        }
        $this->viewData['pageTitle'] = __('Show Merchant Product Template Option');

        $this->viewData['result'] = $option;
        return $this->view('merchant-product-template-option.show',$this->viewData);

    }

    public function edit(MerchantProductTemplateOption $option)
    {

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product Template Option'),
            'url'=> route('system.attributes.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Product Template Option'),
        ];

        $this->viewData['pageTitle'] = __('Edit Merchant Product Template Option');
        $this->viewData['result'] = $option;
        return $this->view('merchant-product-template-option.create',$this->viewData);

    }

    public function update(MerchantProductTemplateOptionFormRequest $request,MerchantProductTemplateOption $option)
    {
        $theRequest = $request->all();


        if($option->update($theRequest)) {
            if ($request->type =='text' || $request->type =='textarea'){
                $option->optionValues()->delete();
            }else {
                $option->optionValues()->delete();
                foreach ($request->option_value_name_ar as $key => $value) {
                    $items['name_ar'] = $request->option_value_name_ar[$key];
                    $items['name_en'] = $request->option_value_name_en[$key];
                    $items['price_prefix'] = $request->option_value_price_prefix[$key];
                    $items['price'] = $request->option_value_price[$key];
                    $option->optionValues()->create($items);
                }
            }
            return redirect()
                ->route('system.option.edit', $option->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully Edit Merchant Product Template Option'));
        } else{
            return redirect()
                ->route('system.option.edit',$option->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Product Template Option'));
        }
    }
    public function destroy(Request $request,MerchantProductTemplateOption $option)
    {
        $option->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Merchant Product Template Option has been deleted successfully')];
        } else {
            redirect()
                ->route('system.option.index')
                ->with('status', 'success')
                ->with('msg', __('Merchant Product Template Option has been deleted'));
        }
    }




    public function merchantOptions(Request $request){
        $template_option =  MerchantProductTemplateOption::select('id','name_'.\DataLanguage::get().' as name')
            ->where('merchant_id',$request->merchant_id)->get()->toArray();
        $return = '<option value="" >'.__('Select Option').'</option>';
        $return .= '<option value="new" >'.__('New Option').'</option>';
        if(!empty($template_option)){
            foreach ($template_option as $key=>$row){
                $return .= '<option value="'.$row['id'].'" >'.$row['name'].'</option>';
            }
        }
        return $return;
    }


}
