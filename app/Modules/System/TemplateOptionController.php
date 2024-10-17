<?php

namespace App\Modules\System;

use App\Http\Requests\MerchantProductTemplateAttributeFormRequest;
use App\Http\Requests\MerchantProductTemplateOptionFormRequest;
use App\Http\Requests\TemplateOptionFormRequest;
use App\Models\ItemCategory;
use App\Models\MerchantProductTemplateOption;
use App\Models\MerchantProductTemplateOptionValues;
use App\Models\TemplateOption;
use App\Models\TemplateOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\UserFormRequest;
class TemplateOptionController extends SystemController
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

            $eloquentData = TemplateOption::select([
                'id',
                'name_'.\DataLanguage::get().' as name',
                'type',
                'is_required',
                'item_category_id'
            ]);


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'DATE(created_at)',$request->created_at1,$request->created_at2);

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
              if($request->status){
                $eloquentData->where('status', '=',$request->status);
            }
            if($request->item_category_id){
                $eloquentData->where('item_category_id', '=',$request->item_category_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('type','{{$type}}')
                ->addColumn('is_required','{{$is_required}}')

               ->addColumn('item_category_id',function ($data){
                    return '<a target="_blank" href="'.route('system.item_category.show',$data->itemCategory->id).'">'.$data->itemCategory->{'name_'.\DataLanguage::get()}.'</a>';
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
                'Type',
                'Is Required',
                'Item Category',
                'Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Template Options')
            ];
            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Template Options');
            }else{
                $this->viewData['pageTitle'] = __('Template Options');
            }



            return $this->view('template-option.index',$this->viewData);
        }

    }

    public function create()
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Template Option'),
            'url'=> route('system.option.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create  Template Option'),
        ];
        $this->viewData['itemCategories'] = array_column(ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');

        $this->viewData['pageTitle'] = __('Create Template Option');
        return $this->view('template-option.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateOptionFormRequest $request)
    {
        $theRequest = $request->all();

        $theRequest['staff_id'] = Auth::id();
        $items = [];
        if (!empty($request->option_value_name_ar)) {
            foreach ($request->option_value_name_ar as $key => $value) {
                $items['name_ar'] = $request->option_value_name_ar[$key];
                $items['name_en'] = $request->option_value_name_en[$key];
                $items['price_prefix'] = $request->option_value_price_prefix[$key];
                $items['price'] = $request->option_value_price[$key];
                $theRequest['values'][$key] = $items;
            }
        }

       // dd($theRequest);
            $option = TemplateOption::create($theRequest);
            if ($option){
            return redirect()
                ->route('system.option.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        }else{
            return redirect()
                ->route('system.option.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Template Option'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(TemplateOption $option,Request $request){
       // dd(unserialize($option->values));
       // dd($option->merchant->id);
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Template Option'),
                // 'url'=> route('system.address.index'),
                'url'=> route('system.option.index'),
            ],
            [
                'text'=> __('Show Template Option'),
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Template Option');

        $this->viewData['result'] = $option;
        $this->viewData['values'] = $option->values;
        return $this->view('template-option.show',$this->viewData);

    }

    public function edit(TemplateOption $option)
    {

        $this->viewData['breadcrumb'][] = [
            'text'=> __(' Template Option'),
            'url'=> route('system.option.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Template Option'),
        ];
        $this->viewData['itemCategories'] = array_column(ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['pageTitle'] = __('Edit  Template Option');
        $this->viewData['result'] = $option;
        return $this->view('template-option.create',$this->viewData);

    }

    public function update(TemplateOptionFormRequest $request,TemplateOption $option)
    {
        $theRequest = $request->all();
        $items = [];
        if ($request->type =='text' || $request->type =='textarea') {
            $theRequest['values'] = $items;
        }else{
                foreach ($request->option_value_name_ar as $key => $value) {
                    $items['name_ar'] = $request->option_value_name_ar[$key];
                    $items['name_en'] = $request->option_value_name_en[$key];
                    $items['price_prefix'] = $request->option_value_price_prefix[$key];
                    $items['price'] = $request->option_value_price[$key];
                    $theRequest['values'][$key] = $items;
                }
            }
        if($option->update($theRequest)) {
            return redirect()
                ->route('system.option.edit', $option->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully Edit Template Option'));
        } else{
            return redirect()
                ->route('system.option.edit',$option->id)
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Template Option'));
        }
    }
    public function destroy(Request $request,TemplateOption $option)
    {
        $option->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Template Option has been deleted successfully')];
        } else {
            redirect()
                ->route('system.option.index')
                ->with('status', 'success')
                ->with('msg', __('Template Option has been deleted'));
        }
    }

}
