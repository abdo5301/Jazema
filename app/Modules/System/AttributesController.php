<?php

namespace App\Modules\System;

use App\Http\Requests\AttributesFormRequest;
use App\Libs\Create;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Item;
use App\Models\ItemCategories;
use App\Models\ItemCategory;
use App\Models\ItemTypes;
use App\Models\User;
use App\Models\UserJob;
use App\Models\UserRelatives;
use App\Models\UsersAddress;
use function Couchbase\basicDecoderV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\UserFormRequest;

class AttributesController extends SystemController
{

    public function __construct()
    {
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->isDataTable) {

                $eloquentData = Attribute::select([
                    'id',
                    'name_' . \DataLanguage::get() . ' as name',
                    'model_type',
                    'model_id',
                    // 'item_type_id',
                    'type',
                    'is_required',
                    'sort',
                    'staff_id',
                    'created_at'
                ]);
                if($request->attr_type == 'item') {
                    $eloquentData->where('model_type', '=', 'App\Models\ItemCategory');
                }
                else{
                    $eloquentData->where('model_type', '=', 'App\Models\UserJob');
                }
            $eloquentData =  $eloquentData->orderBy('sort');



            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData, 'DATE(created_at)', $request->created_at1, $request->created_at2);

            if ($request->id) {
                $eloquentData->where('id', '=', $request->id);
            }

            if ($request->name) {
                $eloquentData->where('name_ar', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->type) {
                $eloquentData->where('type', '=', $request->type);
            }

            if ($request->status) {
                $eloquentData->where('status', '=', $request->status);
            }
            if ($request->staff_id) {
                $eloquentData->where('staff_id', '=', $request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('name', '{{$name}}')
                ->addColumn('type', '{{$type}}')
                ->addColumn('is_required', '{{$is_required}}')
                ->addColumn('staff', function ($data) {
                    if($data->staff_id)
                    return '<a target="_blank" href="' . route('system.staff.show', $data->staff->id) . '">' . $data->staff->Fullname . '</a>';
                    return '--';
                    //  return $data->staff->Fullname;
                })
                ->addColumn('created_at', function ($data) {
                    if ($data->created_at) {
                        return $data->created_at->diffForHumans();
                    }
                    return '--';
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('system.attributes.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('system.attributes.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.attributes.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                              </ul>
                            </div>";
                })
//                ->addColumn('status',function($data){
//                    if($data->status == 'in-active'){
//                        return 'tr-danger';
//                    }
//                })
                ->make(true);

        } else {
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Name',
                'type',
                'Is Required',
                'Created By',
                'Created At',
                'Action'];
            $this->viewData['breadcrumb'][] = [
                'text' => __('Attributes')
            ];
            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Attributes');
            } else {
                $this->viewData['pageTitle'] = __('Attributes ');
            }


            return $this->view('attributes.index', $this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attributes'),
            'url' => route('system.attributes.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Attribute'),
        ];
        $this->viewData['att_type'] = $request->attr_type;
        $this->viewData['itemTypes'] = array_column(ItemTypes::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['itemCategories'] = array_column(ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['userJobs'] = array_column(UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['pageTitle'] = __('Create Attribute');
//        dd($this->viewData);
        return $this->view('attributes.create', $this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //AttributesFormRequest
//      dd($request->all());
        $theRequest = $request->all();
        $validation = [
            'name_ar'                    =>  'required',
            'name_en'                    =>  'required',
            'type'                       =>  'required|in:text,number,textarea,select,multi_select,date,datetime,location,file',
            'is_required'                =>  'required|in:yes,no',
            // 'sort'                       =>  'nullable|numeric'
        ];
        if($request->attr_type =='job'){
            $validation['user_job_id'] = 'required|exists:user_jobs,id';
        }elseif($request->attr_type = 'item'){
            $validation['item_category_id'] = 'required|exists:item_categories,id';
            $validation['item_type_id'] = 'required|exists:item_types,id';
        }
        if ($request->type == 'select'||  $request->type == 'multi_select') {
            $validation['option_value_name_ar'] = 'array';
            $validation['option_value_name_ar.*'] = 'required';
            $validation['option_value_name_en'] = 'array';
            $validation['option_value_name_en.*'] = 'required';
        }
        $validator = Validator::make($theRequest,$validation);
        if($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $theRequest['staff_id'] = Auth::id();
        if ($request->attr_type == 'item') {
            $theRequest['model_type'] = 'App\Models\ItemCategory';
            $theRequest['model_id'] = $request->item_category_id;
        } elseif ($request->attr_type == 'job') {
            $theRequest['model_type'] = 'App\Models\UserJob';
            $theRequest['model_id'] = $request->user_job_id;
        }
        $attribute = Attribute::create($theRequest);

        if ($attribute) {
            if (!empty($request->option_value_name_ar ))
            foreach ($request->option_value_name_ar as $key => $value) {
                $items['name_ar'] = $request->option_value_name_ar[$key];
                $items['name_en'] = $request->option_value_name_en[$key];
                $items['sort'] = $request->option_value_sort[$key];
                $attribute->values()->create($items);
            }
            return ['status'=>true,'data'=>$attribute,'msg'=>__('Attribute has been added successfully')];
//            return redirect()
//                ->route('system.attributes.create')
//                ->with('status', 'success')
//                ->with('msg', __('Data has been added successfully'));
        }
//        else {
//            return redirect()
//                ->route('system.attributes.create')
//                ->with('status', 'danger')
//                ->with('msg', __('Sorry Couldn\'t add Attributes'));
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute, Request $request)
    {
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Item Category'),
                'url' => route('system.attributes.index'),
            ],

        ];
        $this->viewData['pageTitle'] = __('Show Attribute');

        $this->viewData['result'] = $attribute;
        return $this->view('attributes.show', $this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {

//        if(isset($attribute->id) && $attribute->values->isNotEmpty()) {
//            foreach ($attribute->values as $key => $value) {
//               return $value['name_ar'];
//            }
//        }

//        dd(isset($attribute->id));
//        dd($attribute->toArray());
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attribute'),
            'url' => route('system.attributes.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Attribute'),
        ];
        $this->viewData['itemTypes'] = array_column(ItemTypes::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['itemCategories'] = array_column(ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['userJobs'] = array_column(UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray(), 'name', 'id');
        $this->viewData['pageTitle'] = __('Edit Attribute');
        $this->viewData['result'] = $attribute;
        return $this->view('attributes.create', $this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $theRequest = $request->all();

        $validation = [
            'name_ar'                    =>  'required',
            'name_en'                    =>  'required',
            'type'                       =>  'required|in:text,number,textarea,select,multi_select,date,datetime,location,file',
            'is_required'                =>  'required|in:yes,no',
            // 'sort'                       =>  'nullable|numeric'
        ];
        if($request->attr_type =='job'){
            $validation['user_job_id'] = 'required|exists:user_jobs,id';
        }elseif($request->attr_type = 'item'){
            $validation['item_category_id'] = 'required|exists:item_categories,id';
            $validation['item_type_id'] = 'required|exists:item_types,id';
        }
//        if ($request->type == 'select'||  $request->type == 'multi_select') {
//            $validation['option_value_name_ar'] = 'array';
//            $validation['option_value_name_ar.*'] = 'required';
//            $validation['option_value_name_en'] = 'array';
//            $validation['option_value_name_en.*'] = 'required';
//        }
        $validator = Validator::make($theRequest,$validation);
        if($validator->fails()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $theRequest['staff_id'] = Auth::id();
        if ($request->attr_type == 'item') {
            $theRequest['model_type'] = 'App\Models\ItemCategory';
            $theRequest['model_id'] = $request->item_category_id;
        } elseif ($request->attr_type == 'job') {
            $theRequest['model_type'] = 'App\Models\UserJob';
            $theRequest['model_id'] = $request->user_job_id;
        }

        if ($attribute->update($theRequest)) {

            if ($request->type == 'select' || $request->type == 'multi_select') {
                if(!empty($request->new_option_name_ar)){
                    foreach ($request->new_option_name_ar as $key => $row){
                        $option['name_ar'] = $request->new_option_name_ar[$key];
                        $option['name_en'] = $request->new_option_name_en[$key];
                        $option['sort'] = $request->option_value_sort[$key];
                        $attribute->values()->create($option);
                    }
                }
            }


            if ($request->type == 'select' || $request->type == 'multi_select') {
                if(!empty($request->option)){
                    $option = [];
                    foreach ($request->option as $key => $row){
                        $option['name_ar'] = $row['option_value_name_ar'];
                        $option['name_en'] = $row['option_value_name_en'];
                        $option['sort'] = $row['option_value_sort'];
                        AttributeValues::where('id',$key)->update($option);
                    }
                }



            }



            return ['status'=>true,'data'=>$attribute,'msg'=>__('Successfully Edit Attribute')];
        }
//        else {
//            return redirect()
//                ->route('system.attributes.edit')
//                ->with('status', 'danger')
//                ->with('msg', __('Sorry Couldn\'t Edit Attribute'));
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Attribute $attribute)
    {
        $attribute->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Attribute  has been deleted successfully')];
        } else {
            redirect()
                ->route('system.attributes.index')
                ->with('status', 'success')
                ->with('msg', __('This Attribute has been deleted'));
        }
    }


}
