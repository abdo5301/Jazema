<?php

namespace App\Modules\System;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\PagesFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class PagesController extends SystemController
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

            $eloquentData = Page::select([
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
                                  <li class=\"dropdown-item\"><a href=\"" . route('system.pages.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('system.pages.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
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
                $this->viewData['pageTitle'] = __('Deleted Pages');
            } else {
                $this->viewData['pageTitle'] = __('Pages');
            }

            return $this->view('pages.index', $this->viewData);
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
            'text' => __('Pages'),
            'url' => route('system.pages.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Page'),
        ];

        $this->viewData['pageTitle'] = __('Create Page');

        return $this->view('pages.create', $this->viewData);

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
            'content_ar' => 'required|min:3',
            'content_en' => 'required|min:3',
            'image' =>'nullable|image',
        ]);
        $theRequest = $request->all();
        if ($request->file('image')) {
            $theRequest['image'] = $request->image->store('pages/' . date('y') . '/' . date('m'));
        }
        $theRequest['staff_id'] = Auth::id();

        if ($page = Page::create($theRequest)) {
            $data['slug_ar'] = create_slug($theRequest['name_ar'], $page->id);
            $data['slug_en'] = create_slug($theRequest['name_en'], $page->id);
            $page->update($data);
            return redirect()
                ->route('system.pages.create')
                ->with('status', 'success')
                ->with('msg', __('Data has been added successfully'));
        } else {
            return redirect()
                ->route('system.pages.create')
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t add page'));
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
    public function edit(Page $page)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Pages'),
            'url' => route('system.pages.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Page'),
        ];

        $this->viewData['pageTitle'] = __('Edit Page');
        $this->viewData['result'] = $page;

        return $this->view('pages.create', $this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'name_ar' => 'required|min:3',
            'name_en' => 'required|min:3',
            'content_ar' => 'required|min:3',
            'content_en' => 'required|min:3',
            'image' =>'nullable|image',
        ]);
        $theRequest = $request->all();
        if ($request->file('image')) {
            $theRequest['image'] = $request->image->store('pages/' . date('y') . '/' . date('m'));
        }
        $theRequest['slug_ar'] = create_slug($theRequest['name_ar'], $page->id);
        $theRequest['slug_en'] = create_slug($theRequest['name_en'], $page->id);

        if ($page->update($theRequest))
            return redirect()
                ->route('system.pages.edit', $page->id)
                ->with('status', 'success')
                ->with('msg', __('Successfully Edit page'));
        else {
            return redirect()
                ->route('system.pages.edit', $page->id)
                ->with('status', 'danger')
                ->with('msg', __('Sorry Couldn\'t Edit page'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page, Request $request)
    {
        // Delete Data
        $page->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('page has been deleted successfully')];
        } else {
            redirect()
                ->route('system.pages.index')
                ->with('status', 'success')
                ->with('msg', __('This page has been deleted'));
        }
    }
}
