<?php

namespace App\Modules\System;
use App\Libs\OrderData;
use App\Models\Deal;
use App\Models\DealOptionValue;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
class DealController extends SystemController
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
            $eloquentData = Deal::select([
                'id',
                'item_id',
                'item_owner_id',
                'user_id',
                'status',
                'total_price',
                'notes',
                'staff_id',
                'created_at'
            ]);

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
            if ($request->status) {
                $eloquentData->where('status', '=', $request->status);
            }
            if ($request->staff_id) {
                $eloquentData->where('staff_id', '=', $request->staff_id);
            } if ($request->item_id) {
                $eloquentData->where('item_id', '=', $request->item_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('item', function($data){
                    return '<a target="_blank" href="' . route('system.item.show', $data->item->id) . '">' . $data->item->{'name_'.\DataLanguage::get()}. '</a>';
                })
                ->addColumn('user_id', function ($data) {
                    return '<a target="_blank" href="' . route('system.users.show', $data->user->id) . '">' . $data->user->Fullname. '</a>';
                })
                ->addColumn('item_owner_id', function ($data) {
                    if($data->item_owner_id)
                        return '<a target="_blank" href="' . route('system.users.show', $data->owner->id) . '">' . $data->owner->Fullname. '</a>';
                    return '--';
                })
                ->addColumn('total_price',function($data){
                    return amount($data->total_price,true);
                })
                ->addColumn('status', '{{$status}}')
                ->addColumn('staff', function ($data) {
                    return '<a target="_blank" href="' . route('system.staff.show', $data->staff->id) . '">' . $data->staff->Fullname . '</a>';
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
                                <li class=\"dropdown-item\"><a href=\"" . route('system.deal.show', $data->id) . "\">" . __('View') . "</a></li>
                              
                              </ul>
                            </div>";
                })

                ->make(true);

        } else {
            // View Data
            $this->viewData['tableColumns'] = [
                'ID',
                'Item',
                'User',
                'Shared From',
                'Total Price',
                'Status',
                'Created By',
                'Created At',
               'Action'
            ];
            $this->viewData['breadcrumb'][] = [
                'text' => __('Deals')
            ];
            if ($request->withTrashed) {
                $this->viewData['pageTitle'] = __('Deleted Item Categories');
            } else {
                $this->viewData['pageTitle'] = __('Deals');
            }


            return $this->view('jazimaa-deal.index', $this->viewData);
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
            'text' => __('Deal'),
            'url' => route('system.deal.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Deal'),
        ];
        $this->viewData['pageTitle'] = __('Create Deal');
//        dd($this->viewData);
        return $this->view('jazimaa-deal.create', $this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $RequestData = $request->only(['item_owner_id', 'user_id', 'item_id', 'product']);
//        dd($RequestData);
//        foreach ($RequestData['product'] as $key => $value) {
//            dd($value);
//        }
        $validator = Validator::make($RequestData, [
            'item_owner_id' => 'required|integer|exists:users,id',
            'item_id' => 'required|integer|exists:items,id',
            'user_id' => 'required|integer|exists:users,id',
            'product' => 'required|array'
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        foreach ($RequestData['product'] as $row){
            $RequestData['total_price'] = $row['price']* $row['quantity'];
        }

        $deal = Deal::create($RequestData);
        if ($deal){
            foreach ($RequestData['product'] as $key => $value) {
                $orderProductOption['item_id'] = $value['id'];
                $orderProductOption['deal_id'] = $deal->id;
                if (!empty($value['options']) && is_array($value['options'])) {
                    foreach ($value['options'] as $kOption => $vOption) {
                        $orderProductOption = [
                            'item_option_id' => $vOption['id']
                        ];

                        if (is_array($vOption['value'])) {
                            $orderProductOption['prefix_price'] = $vOption['value']['price_prefix'];
                            $orderProductOption['price'] = $vOption['value']['price'];
                            $orderProductOption['item_option_value_id'] = $vOption['value']['id'];
                        } else {
                            $orderProductOption['value'] = $vOption['value'];
                        }
                    }
                }
                DealOptionValue::create($orderProductOption);
            }
            return ['status' =>true,'data'=>$deal];
        }
        return ['status' =>false,'data'=>''];
        // = $RequestData['product']['price'] * $RequestData['product']['quantity'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal, Request $request)
    {
//        dd($deal->options->toArray());
//        dd($deal->toArray());
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Deals'),
                'url' => route('system.deal.index'),
            ],

        ];
        $this->viewData['pageTitle'] = __('Show Deal');

        $this->viewData['result'] = $deal;
        $this->viewData['users'] = User::all();
        $this->viewData['items'] = Item::all();
        return $this->view('jazimaa-deal.show', $this->viewData);

    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Deal $deal)
    {
        return back();
        $deal->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Deal  has been deleted successfully')];
        } else {
            redirect()
                ->route('system.deal.index')
                ->with('status', 'success')
                ->with('msg', __('This Deal has been deleted'));
        }
    }
    
    public function checkProductOption(Request $request)
    {
        if (empty($request->item_id))
            return ['status' => false, 'data' => [], 'msg' => 'please select Item'];
        $item = Item::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('id', $request->item_id)
            ->with(['option' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('status', 'active')->orderBy('sort');
            }, 'option.values' => function ($q) {
                $q->where('status', 'active');
            }])->first();


        $options = [];
        $errors = [];
        if ($item->option) {
            foreach ($item->option as $key => $row) {
                if ($row->is_required == 'yes' && empty($request->option[$row->id])) {
                    $errors[] = $row->id;
                    $errorMsg[] = __('Requied');
                } elseif ($row->is_required == 'no' && empty($request->option[$row->id])) {
                    continue;
                } else {
                    if ($row->type == 'select' || $row->type == 'radio' || $row->type == 'check') {
                        $valueID = $request->option[$row->id];
                        if ($row->type == 'check') {
                            $selectedValue = $row->values()->select('*', 'name_' . \DataLanguage::get() . ' as name')->whereIn('id', $valueID)->get();
                            $options[$key]['value'] = array_column($selectedValue->toArray(), 'name');
                            $options[$key]['value_id'] = array_column($selectedValue->toArray(), 'id');
                            $options[$key]['price_prefix'] = array_column($selectedValue->toArray(), 'price_prefix');
                            $options[$key]['price'] = array_column($selectedValue->toArray(), 'price');;
                        } else {
                            $selectedValue = $row->values()->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->find($valueID);
                            $options[$key]['value'] = $selectedValue->name;
                            $options[$key]['value_id'] = $selectedValue->id;
                            $options[$key]['price_prefix'] = $selectedValue->price_prefix;
                            $options[$key]['price'] = $selectedValue->price;
                        }
                    } else {
                        $options[$key]['value'] = $request->option[$row->id];
                        $options[$key]['value_id'] = $request->option[$row->id];
                        $options[$key]['price_prefix'] = 0;
                        $options[$key]['price'] = 0;
                    }
                    $options[$key]['id'] = $row->id;
                    $options[$key]['name'] = $row->name;
                    $options[$key]['type'] = $row->type;
                }
            }
        }
        if (!empty($errors)) {
            return ['status' => false, 'data' => ['errors' => $errors, 'error_msg' => $errorMsg]];
        }

        $data = [
            'name' => $item->name,
            'product_id' => $item->id,
            'price' => $item->price,
            'option' => $options
        ];
        if ($item->upload()->first())
            $data['img'] = asset('storage/' . $item->upload()->first()->path);
        return ['status' => true, 'data' => $data];
    }

    public function productOption(Request $request)
    {
        if (empty($request->item_id))
            return ['status' => true, 'data' => __('No Item Selected')];
        $product = Item::findOrFail($request->item_id);
//        dd($product->option->toArray());
        if ($product->option->isNotEmpty()) {
            return ['status' => true, 'data' => ['options' => $product->option()->where('status', 'active')->orderBy('sort')
                ->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->with(['values' => function ($q) {
                    $q->where('status', 'active');
                }])->get(),
                'price' => $product->price]];
        } else {
            return ['status' => false, 'data' => ['options' => [], 'price' => $product->price]];
        }
    }

    public function ValidationError($validation, $message)
    {

        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function ($key, $val) {
            return ['key' => $key, 'val' => implode('|', $val)];
        }, array_keys($errorArray), $errorArray), 'val', 'key');


        return response()->json([
            'status' => false,
            'msg' => implode("\n", array_flatten($errorArray)),
            'data' => ['errors' => $data]
        ]);
    }



}
