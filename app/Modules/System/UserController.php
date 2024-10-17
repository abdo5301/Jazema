<?php

namespace App\Modules\System;

use App\Libs\Create;
use App\Models\AreaType;
use App\Models\Attribute;
use App\Models\Deal;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\SelectedAttributeValues;
use App\Models\User;
use App\Models\UserJob;
use App\Models\UserRelatives;
use App\Models\UsersAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserFormRequest;

class UserController extends SystemController
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

            $eloquentData = User::select([
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.email',
                'users.mobile',
                'users.status',
                'users.image',
                'users.gender',
                'users.user_job_id',
                'users.created_at',

            ]);

//dd($eloquentData->get()->toArray());
            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData, 'DATE(users.created_at)', $request->created_at1, $request->created_at2);

            if ($request->id) {
                $eloquentData->where('users.id', '=', $request->id);
            }

            if ($request->name) {
                $eloquentData->whereRaw("CONCAT(`users.firstname`,' ',`users.lastname`) LIKE('%?%')", [$request->name]);
            }

            if ($request->email) {
                $eloquentData->where('users.email', 'LIKE', '%' . $request->email . '%');
            }

            if ($request->mobile) {
                $eloquentData->where('users.mobile', 'LIKE', '%' . $request->mobile . '%');
            }

            if ($request->status) {
                $eloquentData->where('users.status', '=', $request->status);
            }
            if ($request->gender) {
                $eloquentData->where('users.gender', '=', $request->gender);
            }
            if ($request->user_job_id){
//                $eloquentData->userJob()->where('user_job_id',$request->user_job_id);
                $eloquentData = $eloquentData->join('user_jobs', 'user_jobs.id', '=', 'users.user_job_id')
                    ->where('user_jobs.id', $request->user_job_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('user_job_id',function ($data){
                    return $data->userJob->{'name_'. \DataLanguage::get()};
                })
//                ->addColumn('image', function ($data) {
//                    if (!$data->image) {
//                        return '--';
//                    }
//                    else {
//                        return '<img src="' . asset('storage/' . imageResize($data->image, 70, 70)) . '" />';
//                    }
//                })
                ->addColumn('firstname', function ($data) {
                    return $data->firstname . ' ' . $data->lastname;
                })
                ->addColumn('email', '<a href="mailto:{{$email}}">{{$email}}</a>')
                ->addColumn('mobile', '<a href="tel:{{$mobile}}">{{$mobile}}</a>')
                ->addColumn('gender', '{{$gender}}')
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('system.users.show', $data->id) . "\">" . __('View') . "</a></li>
                                 <li class=\"dropdown-item\"><a href=\"" . route('system.users.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.users.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 'in-active') {
                        return 'tr-danger';
                    }
                })
                ->make(true);
        } else {
            // View Data
            $this->viewData['tableColumns'] = ['ID', 'Image', 'Name', 'E-mail',
                'Mobile',
                'Gender', 'Action'];
            $this->viewData['breadcrumb'][] = [
                'text' => __('Users')
            ];
            $this->viewData['country'] = countries();
            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Users');
            } else {
                $this->viewData['pageTitle'] = __('Users');
            }

            $userJobs = UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray();
            $this->viewData['userJobs'] = array_column($userJobs, 'name', 'id');;
            return $this->view('users.index', $this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(country());
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Users'),
            'url' => route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create User'),
        ];
        $userJobs = UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray();
        $this->viewData['userJobs'] = array_column($userJobs, 'name', 'id');
        $itemCategories = ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray();
        $this->viewData['itemCategories'] = array_column($itemCategories, 'name', 'id');
        $this->viewData['areaData'] = AreaType::getFirstArea(\DataLanguage::get());
        $this->viewData['pageTitle'] = __('Create User');
        return $this->view('users.create', $this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->type);
        $location = [$request->latitude, $request->longitude];
        $data = [
            'company_name' => $request->company_name,
            'company_business' => $request->company_business,
            'user_job_id' => $request->user_job_id,
            'type' => $request->type,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'mobile2' => $request->mobile2,
            'mobile3' => $request->mobile3,
            'area_id' => getLastNotEmptyItem($request->area_id),
            'interisted_categories' => $request->interisted_categories,
            'address' => $request->address,
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'linkedin' => $request->linkedin,
            'instgram' => $request->instgram,
            'google' => $request->google,
            'location' => implode(',', $location),
            'about' => $request->about,
            'image' => $request->image,
            'attribute' => $request->attribute,
            'staff_id' => Auth::id()
        ];
        //  $theRequest = $request->all();
        //  $theRequest['staff_id'] = Auth::id();
        $user = new Create();
        return $user->User($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Request $request)
    {
//        dd(url());
//          dd($user->toArray());
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Users'),
                'url' => route('system.users.index'),
            ],
            [
                'text' => $user->firstname . ' ' . $user->lastname,
            ]
        ];
        if ($request->isDataTable) {
            $eloquentData = ItemCategory::whereIn('id', explode(',', $user->interisted_categories))->select([
                'id',
                'name_' . \DataLanguage::get() . ' as name',
                'parent_id',
                'description_' . \DataLanguage::get() . ' as description',
                'icon',
                'status',
                'sort',
                'staff_id'
            ])->orderBy('sort');

            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('icon', function ($data) {
                    if (!$data->icon) return '--';
//                    return '<img  style="width:70px;height:70px" src="' . url('storage/' .$data->icon). '" />';
                    return '<img src="' .  img($data->icon, 70, 70) . '" />';
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
                ->make(true);

        }

        if ($request->isItem) {

            $eloquentData = Item::select([
                'id',
                'item_category_id',
                'item_type_id',
                'user_id',
                'name_' . \DataLanguage::get() . ' as name',
                'price',
                'quantity',
                'status',
                'staff_id',
                'created_at',

            ])->where('user_id', $user->id);
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('item_category_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.item_category.show', $data->item_category->id) . '">' . $data->item_category->{'name_' . \DataLanguage::get()} . '</a>';
                })
                ->addColumn('item_type_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.item_type.show', $data->item_type->id) . '">' . $data->item_type->{'name_' . \DataLanguage::get()} . '</a>';
                })
                ->addColumn('price', function ($data) {
                    return amount($data->price, true);
                })
                ->addColumn('quantity', '{{$quantity}}')
                ->addColumn('status', '{{$status}}')
                ->addColumn('created_at', function ($data) {
                    if (!empty($data->created_at))
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.edit', $data->id) . "\">" . __('View') . "</a></li>                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item_category.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>

                              </ul>
                            </div>";
                })
                ->make(true);
        }
        if ($request->isDeal) {

            $eloquentData = Deal::select([
                'id',
                'item_id',
                'item_owner_id',
                'user_id',
                'total_price',
                'status',
                'staff_id',
                'created_at',
            ])->where('user_id', $user->id);
            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('item_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.item.show', $data->item->id) . '">' . $data->item->{'name_' . \DataLanguage::get()} . '</a>';
                })
                ->addColumn('item_owner_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.users.show', $data->owner->id) . '">' . $data->owner->Fullname . '</a>';
                })
                ->addColumn('total_price', function ($data) {
                    return amount($data->total_price, true);
                })
                ->addColumn('status', '{{$status}}')
                ->addColumn('staff_id', function ($data) {
                    if (!empty($data->staff_id))
                        return '<a target="_blank" href="' . route('system.staff.show', $data->staff->id) . '">' . $data->staff->Fullname . '</a>';
                    return '--';
                })
                ->addColumn('created_at', function ($data) {
                    if (!empty($data->created_at))
                        return $data->created_at->diffForHumans();
                    return '--';
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('system.item.edit', $data->id) . "\">" . __('View') . "</a></li>                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item_category.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.item.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>

                              </ul>
                            </div>";
                })
                ->make(true);
        }

        $this->viewData['pageTitle'] = __('Show Users');

        $this->viewData['result'] = $user;
        return $this->view('users.show', $this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Users'),
            'url' => route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit User'),
        ];

        $this->viewData['pageTitle'] = __('Edit User');
        $this->viewData['result'] = $user;
        $current_attribute = SelectedAttributeValues::where(['model_id' => $user->id, 'model_type' => 'App\Models\User'])->get();
//        dd( $current_attribute );
        $this->viewData['current_attribute'] = $current_attribute;
        $userJobs = UserJob::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray();
        $this->viewData['userJobs'] = array_column($userJobs, 'name', 'id');
        $itemCategories = ItemCategory::get(['id', 'name_' . \DataLanguage::get() . ' as name'])->toArray();
        $this->viewData['itemCategories'] = array_column($itemCategories, 'name', 'id');
        $this->viewData['areaData'] = AreaType::getFirstArea(\DataLanguage::get());
        $this->viewData['pageTitle'] = __('Create User');

        return $this->view('users.create', $this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $theRequest = $request->all();
        // dd($theRequest['area_id'][0]);
        $user = new Create();
        return $user->EditUserProfile($theRequest);
//        $validationArray = [
//            'type' => 'required|in:individual,company',
//            'user_job_id' => 'required|exists:user_jobs,id',
//            'firstname' => 'required',
//            'lastname' => 'required',
//            'email' => 'required|unique:users,email,' . $user->id,
//            'password' => 'nullable|confirmed',
//            'password_confirmation' => 'required_with:password',
//            'gender' => 'required|in:male,female',
//            'phone' => 'numeric',
//            'mobile' => 'required|numeric|unique:users,mobile,' . $user->id,
//            'mobile2' => 'nullable|numeric',
//            'mobile3' => 'nullable|numeric',
//            // 'area_id'       =>  'nullable|exists:areas,id',
//            'interisted_categories' => 'array',
//            'facebook' => 'nullable|url',
//            'youtube' => 'nullable|url',
//            'linkedin' => 'nullable|url',
//            'google' => 'nullable|url',
//            'instgram' => 'nullable|url',
//        ];
//        if ($theRequest['type'] == 'company') {
//            $validationArray['company_name'] = 'required';
//            $validationArray['company_business'] = 'required';
//        }
//
//        // validate user attribute
//        if (!empty($theRequest['user_job_id'])) {
//            $user_job_attributes = Attribute::where('model_id', $theRequest['user_job_id'])
//                ->where('model_type', 'App\Models\UserJob')->get(['*', 'name_' . \DataLanguage::get() . ' as name']);
//            if (!empty($user_job_attributes)) {
//                foreach ($user_job_attributes as $key => $attribute) {
//                    $type = '';
//                    $required = '';
//
//                    if ($attribute->is_required == 'yes')
//                        $required = 'required';
//                    if ($attribute->type == 'date')
//                        $type = '|date';
//                    if ($attribute->type == 'datetime')
//                        $type = '|datetime';
//                    if ($attribute->type == 'image')
//                        $type = '|image';
//                    if ($attribute->type == 'select')
//                        $type = 'array|numeric';
//                    if ($attribute->type == 'multi_select')
//                        $type = '|array';
//
//                    $validationArray['attribute[' . $attribute->id . ']'] = $required . $type;
//                }
//            }
//        }
//
//
//        $validator = Validator::make($theRequest, $validationArray);
//        if ($validator->fails()) {
//            return $this->ValidationError($validator, __('Validation Error'));
//        }
//        if ($request->file('image')) {
//            $theRequest['image'] = $request->image->store('users/' . date('y') . '/' . date('m'));
//        } else {
//            unset($theRequest['image']);
//        }
//
//        if ($request->password) {
//            $theRequest['password'] = bcrypt($theRequest['password']);
//        } else {
//            unset($theRequest['password']);
//        }
//        if ($request->area_id && $theRequest['area_id'][0] != 0) {
//            // dd('here');
//            $theRequest['area_id'] = getLastNotEmptyItem($request->area_id);
//        } else {
//            //dd('not here');
//            unset($theRequest['area_id']);
//        }
//        $theRequest['interisted_categories'] = implode(',', $theRequest['interisted_categories']);
//        $name = strstr($request->email, '@', true);
//        $theRequest['slug'] = create_slug($name, $user->id);
//        if ($user->update($theRequest))
//            return ['status' => true, 'data' => $user, 'msg' => __('user is Updatde Successfully')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('User has been deleted successfully')];
        } else {
            redirect()
                ->route('system.users.index')
                ->with('status', 'success')
                ->with('msg', __('User has been deleted'));
        }
    }


    function getAttributes(Request $request)
    {
        if (!$request->user_job_id)
            return ['status' => false, 'msg' => __('please select user Job')];
        $attribute = Attribute::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->orderBy('sort')
            ->where(['model_id' => $request->user_job_id, 'model_type' => 'App\Models\UserJob'])->with(['values' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->get();
            }])->get();

//        dd($attribute->toArray());
        if (empty($attribute->toArray()))
            return ['status' => false];

        return ['status' => true, 'data' => $attribute];
    }

}
