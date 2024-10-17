<?php

namespace App\Modules\System;

use App\Http\Requests\MerchantProductTemplateAttributeFormRequest;
use App\Http\Requests\TaxesFormRequest;
use App\Http\Requests\UserAddressesFormRequest;
use App\Http\Requests\UserRelativeRelationFormRequest;
use App\Http\Requests\UserRelativesFormRequest;
use App\Libs\Create;
use App\Models\AreaType;
use App\Models\MerchantProductTemplateAttribute;
use App\Models\ProductAttribute;
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
class ProductAttributeController extends SystemController
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

            $eloquentData = ProductAttribute::select([
                'id',
                'name_'.\DataLanguage::get().' as name',
                'type',
                'is_required',
                'sort',
                'merchant_product_category_id'
            ]);


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'DATE(created_at)',$request->created_at1,$request->created_at2);
            whereBetween($eloquentData,'rate',$request->rate1,$request->rate2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->name){
                $eloquentData->where('name_ar', 'LIKE',$request->name)
                ->orWhere('name_en','LIKE','%'.$request->name.'%');
            }
             if($request->description){
                $eloquentData->where('description_ar', 'LIKE',$request->description)
                ->orWhere('description_en','LIKE','%'.$request->description.'%');
            }

            if($request->type){
                $eloquentData->where('type', '=',$request->type);
            }
            if($request->staff_id){
                $eloquentData->where('staff_id', '=',$request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('description',function ($data){
                    if ($data->description)
                    return str_limit($data->description,25);
                    return '--';
                })

                ->addColumn('staff_id',function ($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant->id).'">'.$data->merchant->{'name_'.\DataLanguage::get()}.'</a>';
                })

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.attributes.edit',$data->id)."\">".__('Edit')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Name',
                'Description',
                'Merchant',
                'Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Product Template Attribute')
            ];
            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Product Template Attribute');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Product Template Attribute');
            }



            return $this->view('product-attribute.index',$this->viewData);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product Template Attribute'),
            'url'=> route('system.attributes.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create  Product Template Attribute'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Product Template Attribute');
        return $this->view('product-attribute.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantProductTemplateAttributeFormRequest $request)
    {
        $theRequest = $request->all();

        $theRequest['staff_id'] = Auth::id();

        if (MerchantProductTemplateAttribute::create($theRequest))
            return redirect()
                ->route('system.attributes.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.attributes.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Product Template Attribute'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantProductTemplateAttribute $attribute){
       // dd($tax->toArray());
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Product Template Attribute'),
                // 'url'=> route('system.address.index'),
                'url'=> route('system.attributes.index'),
            ],
            [
                'text'=> __('Show Product Template Attribute'),
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Merchant Product Template Attribute');

        $this->viewData['result'] = $attribute;
        return $this->view('product-attribute.show',$this->viewData);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantProductTemplateAttribute $attribute)
    {
        //   dd($relative->toArray());

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Product Template Attribute'),
            'url'=> route('system.attributes.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Product Template Attribute'),
        ];

        $this->viewData['pageTitle'] = __('Edit Merchant Product Template Attribute');
        $this->viewData['result'] = $attribute;

        return $this->view('product-attribute.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantProductTemplateAttributeFormRequest $request,MerchantProductTemplateAttribute $attribute)
    {

        $theRequest = $request->all();

        if($attribute->update($theRequest))
            return redirect()
                ->route('system.attributes.edit',$attribute->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant Product Template Attribute'));
        else{
            return redirect()
                ->route('system.attributes.edit',$attribute->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Product Template Attribute'));
        }
    }


    function getAttributes(Request $request){
        if(!$request->category_id)
            return ['status'=>false,'msg'=>__('please select category')];
       $attribute = ProductAttribute::select(['*','name_'.\DataLanguage::get().' as name'])->orderBy('sort')->where('merchant_product_category_id',$request->category_id)->with(['values'=>function($q){
           $q->select(['*','name_'.\DataLanguage::get().' as name'])->get();
       }])->get();
       if(empty($attribute->toArray()))
           return ['status'=>false];

       return ['status'=>true,'data'=>$attribute];
    }


}
