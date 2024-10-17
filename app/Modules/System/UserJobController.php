<?php

namespace App\Modules\System;

use App\Libs\Create;
use App\Models\ItemTypes;
use App\Models\User;
use App\Models\UserJob;
use App\Models\UserRelatives;
use App\Models\UsersAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\UserFormRequest;
class UserJobController extends SystemController
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
        //dd(\DataLanguage::get());
//        dd(app()->getLocale());
        if($request->isDataTable){

            $eloquentData = UserJob::select([
                'id',
                'staff_id',
                'name_ar  as name',
                'created_at'
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
                //$eloquentData->whereRaw("CONCAT(`firstname`,' ',`lastname`) LIKE('%?%')",[$request->name]);
                $eloquentData->where('name_ar','LIKE','%'.$request->name.'%')
                ->orWhere('name_en','LIKE','%'.$request->name.'%');
            }
            if($request->staff_id){
                $eloquentData->where('staff_id','=',$request->staff_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('staff_id',function($data){
                    if($data->staff_id){
                        return $data->staff->Fullname;
                    }
                    return '--';
                })
                ->addColumn('created_at',function($data){
                    if($data->created_at){
                        return $data->created_at->diffForHumans();
                    }
                    return '--';
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.job.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.job.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Name',
                'Created By',
                'Created At',
                'Action'];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('User Jobs')
            ];
            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted User Jobs');
            }else{
                $this->viewData['pageTitle'] = __('User Jobs');
            }

            return $this->view('user-jobs.index',$this->viewData);
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
            'text'=> __('User Jobs'),
            'url'=> route('system.job.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create User Job'),
        ];
         $item_types = array_column(ItemTypes::get(['id','name_'.\DataLanguage::get().' as name'] )->toArray(),'name','id');
        
        $this->viewData['pageTitle'] = __('Create User Job');
        return $this->view('user-jobs.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name_ar' =>'required',
            'name_en' =>'required',
        ]);
        $theRequest = $request->all();
      $theRequest['staff_id'] = Auth::id();

        if(UserJob::create($theRequest))
            return redirect()
                ->route('system.job.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.job.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add User Job'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user,Request $request){
      return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(UserJob $job)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('User Job'),
            'url'=> route('system.job.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit User Job'),
        ];

        $this->viewData['pageTitle'] = __('Edit User Job');
        $this->viewData['result'] = $job;

        return $this->view('user-jobs.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserJob $job)
    {
       $this->validate($request,[
           'name_ar' =>'required',
           'name_en' =>'required'
       ]);
        $theRequest = $request->all();

        if($job->update($theRequest))
            return redirect()
                ->route('system.job.edit',$job->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit User Job'));
        else{
            return redirect()
                ->route('system.job.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit User Job'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,UserJob $job)
    {
        $job->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('User Job  has been deleted successfully')];
        } else {
            redirect()
                ->route('system.job.index')
                ->with('status', 'success')
                ->with('msg', __('This User Job has been deleted'));
        }
    }


}
