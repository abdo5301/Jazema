<?php

namespace App\Modules\System;

use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\PaymentInvoice;
use App\Models\PermissionGroup;
use App\Models\Staff;
use App\Models\WalletTransaction;
use App\Models\StaffSalesLog;
use App\Models\MerchantStaffLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Form;
use App\Http\Requests\StaffFormRequest;
use Spatie\Activitylog\Models\Activity;
use App\Libs\Create;
use App\Notifications\UserNotification;
use App\Libs\SMS;


class StaffController extends SystemController
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

            $eloquentData = Staff::select([
                'id',
                'firstname',
                'lastname',
                'email',
                'status',
                'mobile',
                'permission_group_id',
                'avatar',
                'created_at'])
                ->with('permission_group');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'DATE(created_at)',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id','=',$request->id);
            }

                if($request->name){
                $eloquentData->where('firstname','LIKE','%'.$request->name.'%')
                    ->orWhere('lastname','LIKE','%'.$request->name.'%')
                ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),'LIKE','%'.$request->name.'%');
            }


            if($request->email){
                $eloquentData->where('email','LIKE','%'.$request->email.'%');
            }

            if($request->mobile){
                $eloquentData->where('mobile','LIKE','%'.$request->mobile.'%');
            }

            if($request->gender){
                $eloquentData->where('gender','=',$request->gender);
            }

            whereBetween($eloquentData,'birthdate',$request->birthdate1,$request->birthdate2);

            if($request->job_title){
                $eloquentData->where('job_title','LIKE','%'.$request->job_title.'%');
            }

            if($request->permission_group_id){
                $eloquentData->where('permission_group_id','=',$request->permission_group_id);
            }

            if($request->status){
                $eloquentData->where('status','=',$request->status);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('avatar',function($data){
                    if(!$data->avatar) return '--';
                    return '<img src="'.asset('storage/'.image($data->avatar,70,70)).'" />';
                })
                ->addColumn('firstname', function($data){
                    return $data->firstname.' '.$data->lastname;
                })
             ->addColumn('email','<a href="mailto:{{$email}}">{{$email}}</a>')

                ->addColumn('mobile','<a href="tel:{{$mobile}}">{{$mobile}}</a>')
//                ->addColumn('email','<a href="mailto:{{$email}}">{{$email}}</a>')
                ->addColumn('permission_group',function($data){
                    return '<a href="'.route('system.permission-group.edit',$data->permission_group->id).'">'.$data->permission_group->name.'</a>';
                })

                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.staff.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.staff.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.staff.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Image'),
                __('Name'),
                __('E-mail'),
                __('Mobile'),
                __('Permission Group'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Staff')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Staff');
            }else{
                $this->viewData['pageTitle'] = __('Staff');
            }

            $this->viewData['PermissionGroup'] = array_column(PermissionGroup::get()->toArray(),'name','id');

            return $this->view('staff.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Staff'),
            'url'=> route('system.staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Staff'),
        ];

        $this->viewData['pageTitle'] = __('Create Staff');

        $this->viewData['PermissionGroup'] = PermissionGroup::get();


        return $this->view('staff.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('avatar')) {
            $theRequest['avatar'] = $request->avatar->store('staff/'.date('y').'/'.date('m'));
        }
        $theRequest['password'] = bcrypt($theRequest['password']);

        if($insertedStaff = Create::Staff($theRequest)){

            return redirect()
                ->route('system.staff.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        }else{
            return redirect()
                ->route('system.staff.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Add Staff'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff,Request $request){
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Staff'),
                'url'=> route('system.staff.index'),
            ],
            [
                'text'=> $staff->firstname.' '.$staff->lastname,
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Staff');


        $this->viewData['result'] = $staff;
        return $this->view('staff.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff,Request $request){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        if($request->changePassword == 'true'){
            $newPassword = rand(111111,999999);
            $staff->update([
                'password'=> bcrypt($newPassword)
            ]);
            $SMS = new SMS();
            $SMS->Send($staff->mobile,str_replace(['{1}','{2}'],[$staff->email,$newPassword],setting('sms_on_staff_create')));

            if(!empty(setting('monitor_staff'))){
                $monitorStaff = Staff::whereIn('id',explode("\n",setting('monitor_staff')))
                    ->get();

                foreach ($monitorStaff as $key => $value){
                    $value->notify(
                        (new UserNotification([
                            'title'         => __('Change Staff Password'),
                            'description'   => __(':username has been change :merchantname\'s password',['username'=>Auth::user()->fullname,'merchantname'=>$staff->fullname]),
                            'url'           => route('system.staff.edit',['id'=>$staff->id])
                        ]))
                            ->delay(5)
                    );
                }
            }

            return 'true';
        }


        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Staff'),
            'url'=> route('system.staff.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Staff'),
        ];

        $this->viewData['pageTitle'] = __('Edit Staff');
        $this->viewData['result'] = $staff;
        $this->viewData['PermissionGroup'] = PermissionGroup::get();

        return $this->view('staff.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StaffFormRequest $request, Staff $staff)
    {

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $theRequest = $request->all();
        if($request->file('avatar')) {
            $theRequest['avatar'] = $request->avatar->store('staff/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['avatar']);
        }

        if($request->password){
            $theRequest['password'] = bcrypt($theRequest['password']);
        }else{
            unset($theRequest['password']);
        }


        if($staff->update($theRequest)){

            return redirect()
                ->route('system.staff.edit',$staff->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Staff'));
        }else{
            return redirect()
                ->route('system.staff.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Staff'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff,Request $request)
    {
        if($staff->id == '1'){
            return ['false'=> true,'msg'=> __('Can\'t delete this Staff')];

        }
        $staff->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Staff has been deleted successfully')];
        }else{
            redirect()
                ->route('system.staff.index')
                ->with('status','success')
                ->with('msg',__('This Staff has been deleted'));
        }
    }
}
