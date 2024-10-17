<?php

namespace App\Modules\System;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\PagesFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class ServicesController extends SystemController
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

            $eloquentData = Service::select([
                'id',
                'name_' . \DataLanguage::get() . ' as name',
                'staff_id',
                'created_at',
            ]);

            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('name', '{{$name}}')
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                  <li class=\"dropdown-item\"><a href=\"" . route('system.services.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.services.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        } else {
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('Created At'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text' => __('Pages')
            ];

            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Services');
            } else {
                $this->viewData['pageTitle'] = __('Services');
            }

            return $this->view('services.index', $this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Services'),
            'url' => route('system.services.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Service'),
        ];

        $this->viewData['pageTitle'] = __('Create Service');

        return $this->view('services.create', $this->viewData);

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
            'name_ar' => 'required|min:3',
            'name_en' => 'required|min:3',
            'description_ar' => 'required|min:3',
            'description_en' => 'required|min:3',
            'image' =>'nullable|image',
        ]);
        $theRequest = $request->all();
        if ($request->file('image')) {
            $theRequest['image'] = $request->image->store('services/' . date('y') . '/' . date('m'));
        }
        $theRequest['staff_id'] = Auth::id();

        if ($page = Service::create($theRequest)) {
            $data['slug_ar'] = create_slug($theRequest['name_ar'], $page->id);
            $data['slug_en'] = create_slug($theRequest['name_en'], $page->id);
            $page->update($data);
            return redirect()
                ->route('system.services.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()
                ->route('system.services.create')
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t add service'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */

    public function show()
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Service'),
            'url' => route('system.services.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Page'),
        ];

        $this->viewData['pageTitle'] = __('Edit Service');
        $this->viewData['result'] = $service;

        return $this->view('services.create', $this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Service $service)
    {
        $this->validate($request, [
            'name_ar' => 'required|min:3',
            'name_en' => 'required|min:3',
            'description_ar' => 'required|min:3',
            'description_en' => 'required|min:3',
            'image' =>'nullable|image',
        ]);
        $theRequest = $request->all();
        if ($request->file('image')) {
            $theRequest['image'] = $request->image->store('services/' . date('y') . '/' . date('m'));
        }
        $theRequest['slug_ar'] = create_slug($theRequest['name_ar'], $service->id);
        $theRequest['slug_en'] = create_slug($theRequest['name_en'], $service->id);

        if ($service->update($theRequest))
            return redirect()
                ->route('system.services.edit', $service->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully Edit Service'));
        else {
            return redirect()
                ->route('system.services.edit', $service->id)
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t Edit Service'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service, Request $request)
    {
        // Delete Data
        $service->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Service has been deleted successfully')];
        } else {
            redirect()
                ->route('system.services.index')
                ->with('status', 'success')
                ->with('msg', __('This Service has been deleted'));
        }
    }
}
