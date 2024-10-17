<?php

namespace App\Modules\System;

use App\Libs\Create;
use App\Models\Item;
use App\Models\ItemCategories;
use App\Models\ItemCategory;
use App\Models\User;
use App\Models\UserRelatives;
use App\Models\UsersAddress;
use function Couchbase\basicDecoderV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\UserFormRequest;

class ItemCategoryController extends SystemController
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

            $eloquentData = ItemCategory::select([
                'id',
                'name_' . \DataLanguage::get() . ' as name',
                'parent_id',
                'description_' . \DataLanguage::get() . ' as description',
                'icon',
                'status',
                'sort',
                'staff_id'
            ])->orderBy('sort');


            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData, 'DATE(created_at)', $request->created_at1, $request->created_at2);

            if ($request->id) {
                $eloquentData->where('id', '=', $request->id);
            }

            if ($request->name) {
                $eloquentData->where('name_ar', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->status) {
                $eloquentData->where('status', '=', $request->status);
            }
            if ($request->staff_id) {
                $eloquentData->where('staff_id', '=', $request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('icon', function ($data) {
                    if (!$data->icon) return '--';
                    return '<img  width="70px" height="70px"  src="' . img($data->icon) . '" />';
                })
                ->addColumn('name', '{{$name}}')
                ->addColumn('description', function ($data) {
                    if ($data->description)
                        return '<code>' . str_limit($data->description, 25) . '</code>';
                    return '<code>' . '--' . '</code>';
                })
                // ->addColumn('status','{{$status}}')
                ->addColumn('staff', function ($data) {
                    return $data->staff->Fullname;
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
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item_category.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item_category.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item_category.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
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
            $this->viewData['tableColumns'] = ['ID', 'Icon', 'Name', 'Description', 'Created By', 'Created At', 'Action'];
            $this->viewData['breadcrumb'][] = [
                'text' => __('Item Categories')
            ];
            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Item Categories');
            } else {
                $this->viewData['pageTitle'] = __('Item Categories');
            }


            return $this->view('item-category.index', $this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        dd(getCategoryTreeSelect());
        $this->viewData['breadcrumb'][] = [
            'text' => __('Item Category'),
            'url' => route('system.item_category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Item Category'),
        ];

        $this->viewData['pageTitle'] = __('Create Item Category');
        return $this->view('item-category.create', $this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'parent_id' => 'nullable|exists:item_categories,id',
            'icon' => 'nullable|image',
            'status' => 'required|in:active,in-active',
        ]);
        $theRequest = $request->all();
        if ($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('itemCategories/' . date('y') . '/' . date('m'));
        }
        $theRequest['staff_id'] = Auth::id();

      $item_category = ItemCategory::create($theRequest);
        if ($item_category) {
         $slug_ar = create_slug($item_category->name_ar,$item_category->id);
            $slug_en = create_slug($item_category->name_en,$item_category->id);
            $item_category->update(['slug_ar'=>$slug_ar,'slug_en'=>$slug_en]);
            return redirect()
                ->route('system.item_category.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
       } else {
            return redirect()
                ->route('system.item_category.create')
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t add User'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(ItemCategory $item_category, Request $request)
    {
//        dd($item_category->toArray());
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Item Category'),
                'url' => route('system.item_category.index'),
            ],

        ];

        if ($request->isDataTable) {

            $eloquentData = Item::select([
                'id',
                'item_category_id',
                'user_id',
                'name_' . \DataLanguage::get() . ' as name',
                'price',
                'quantity',
                'status',
                'staff_id',
                'created_at'
            ])
                ->where('item_category_id', $item_category->id);
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('user_id', function ($data) {
                    if (!empty($data->user_id)) {
                        // return '<a target="_blank" href="' . route('system.users.show', $data->user->id) . '">' . $data->user->fullname . '</a>';
                    }
                    return '--';
                })
                ->addColumn('item_category_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.item_category.show', $data->item_category->id) . '">' . $data->item_category->{'name_' . \DataLanguage::get()} . '</a>';
                })
                ->addColumn('name', '{{$name}}')
                ->addColumn('price', function ($data) {
                    return amount($data->price, true);
                })
                ->addColumn('quantity', '{{$quantity}}')
                ->addColumn('status', '{{$status}}')
                ->addColumn('staff_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.staff.show', $data->staff->id) . '">' . $data->staff->Fullname . '</a>';

                })
                ->addColumn('created_at', function ($data) {
                    if ($data->created_at)
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                               <li class=\"dropdown-item\"><a href=\"" . route('system.item.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>

                              </ul>
                            </div>";
                })
                ->make(true);
        }


        $this->viewData['pageTitle'] = __('Show Item Category');

        $this->viewData['result'] = $item_category;
        return $this->view('item-category.show', $this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemCategory $item_category)
    {
//        dd($item_category);
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Item Category'),
            'url' => route('system.item_category.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Item Category'),
        ];

        $this->viewData['pageTitle'] = __('Edit Item Category');
        $this->viewData['result'] = $item_category;
        return $this->view('item-category.create', $this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemCategory $item_category)
    {
        $this->validate($request, [
            'name_ar' => 'required',
            'name_en' => 'required',
            'parent_id' => 'nullable|exists:item_categories,id',
            'icon' => 'nullable|image',
            'status' => 'required|in:active,in-active',
        ]);

        $theRequest = $request->all();
        $theRequest['slug_ar'] = create_slug($item_category->name_ar,$item_category->id);
        $theRequest['slug_en'] = create_slug($item_category->name_en,$item_category->id);
        if ($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('itemCategories/' . date('y') . '/' . date('m'));
        }
//        if ($item_category->id == 1) {
//            $theRequest['parent_id'] = 0;
//        }
        if ($item_category->update($theRequest))
            return redirect()
                ->route('system.item_category.edit', $item_category->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully Edit User'));
        else {
            return redirect()
                ->route('system.item_category.edit')
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t Edit User'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ItemCategory $item_category)
    {
        $item_category->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Item Category  has been deleted successfully')];
        } else {
            redirect()
                ->route('system.item_category.index')
                ->with('status', 'success')
                ->with('msg', __('This Item Category  has been deleted'));
        }
    }


}
