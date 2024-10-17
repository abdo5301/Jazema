<?php

namespace App\Modules\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\ContactUs;

class ContactUsController extends SystemController
{


    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Contact Us')
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
            $systemLang = \DataLanguage::get();
            $eloquentData = ContactUs::select('*');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'DATE(contact_us.created_at)',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('contact_us.id', '=',$request->id);
            }

            if($request->name){
                $eloquentData->where('contact_us.name','LIKE',"%{$request->name}%");
            }

            if($request->email){
                $eloquentData->where('contact_us.email','LIKE',"%{$request->email}%");
            }

            if($request->mobile){
                $eloquentData->where('contact_us.mobile','LIKE',"%{$request->mobile}%");
            }

            if($request->subject){
                $eloquentData->where('contact_us.subject','LIKE',"%{$request->subject}%");
            }

            if($request->is_seen == 'yes'){
                $eloquentData->whereNotNull('contact_us.read_by_staff_id');
            }elseif($request->is_seen == 'no'){
                $eloquentData->whereNull('contact_us.read_by_staff_id');
            }

            if($request->downloadExcel == "true") {
                if (staffCan('download.contact-us.excel')) {
                    $excelData = $eloquentData;
                    $excelData = $excelData->get();

                    exportXLS(__('Contact Us'),
                        [
                            __('ID'),
                            __('Name'),
                            __('Email'),
                            __('Mobile'),
                            __('subject'),
                            __('message'),
                            __('Read By Staff'),
                            __('Created At'),
                        ],
                        $excelData,
                        [
                            'id' => 'id',
                            'name' => 'name',
                            'email' => 'email',
                            'mobile'=>'mobile',
                            'subject' =>'subject',
                            'message'=>'message',
                            'read_by_staff'=>function($data){
                                //return $data->read_by_staff_id;
                                if ($data->read_by_staff_id) {
                                    return $data->readBy->Fullname;
                                }
                                return '--';
//                                $firtsName = Staff::where('id',$data->read_by_staff_id)->value('firstname');
//                                $lastName = Staff::where('id',$data->read_by_staff_id)->value('lastname');
//                                return $firtsName .' '.$lastName;
                            },
                            'created_at'=>function($data){
                                return $data->created_at->format('Y-m-d h:i A');
                            },
                        ]
                    );
                }
            }



return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('mobile','<a href="tel:{{$mobile}}">{{$mobile}}</a>')
                ->addColumn('subject','{{$subject}}')
                ->addColumn('created_at',function($data){
                    return $data->created_at->format('Y-m-d h:i A');
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"javascript:;\" onclick='showData(\"".$data->id."\")'>".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.contact-us.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('read_by_staff_id',function($data){
                    if($data->read_by_staff_id == null){
                        return 'aliceblue-table';
                    }
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('Mobile'),
                __('Subject'),
                __('Created At'),
                __('Action')
            ];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Contact Us');
            }else{
                $this->viewData['pageTitle'] = __('Contact Us');
            }


            return $this->view('contact-us.index',$this->viewData);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contact_us){

        if(is_null($contact_us->read_by_staff_id)){
            $contact_us->update([
                'read_by_staff_id'=> Auth::id()
            ]);
        }
        $this->viewData['result'] = $contact_us;
        return $this->view('contact-us.show',$this->viewData);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactUs $contact_us,Request $request){
        // Delete Data
        $contact_us->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Contact Us has been deleted successfully')];
        }else{
            redirect()
                ->route('system.contact-us.index')
                ->with('status','success')
                ->with('msg',__('This Contact Us has been deleted'));
        }
    }




}
