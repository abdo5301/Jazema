<?php

namespace App\Modules\System;

use App\Libs\DataLanguage;
use App\Libs\OrderData;
use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\MerchantBranch;
use App\Models\MerchantProduct;
use App\Models\Tax;
use App\Models\Order;
use App\Models\User;
use App\Models\UsersAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Http\Request;

class MerchantOrderController extends SystemController
{
    public function __construct()
    {
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text' => __('Home'),
                'url' => url('system'),
            ],
            [
                'text' => __('Merchant'),
                'url' => url('system/merchant')
            ]
        ];
    }

    public function index(Request $request)
    {
//        dd(Order::viewData(\DataLanguage::get())->get()->toArray());
        if ($request->isDataTable) {
            $eloquentData = Order::viewData(\DataLanguage::get());

            if ($request->withTrashed) {
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData, 'orders.created_at', $request->created_at1, $request->created_at2);
            whereBetween($eloquentData, 'orders.total_price', $request->total_price1, $request->total_price2);
            whereBetween($eloquentData, 'orders.tax', $request->tax1, $request->tax2);
            whereBetween($eloquentData, 'orders.delivery', $request->delivery1, $request->delivery2);
            whereBetween($eloquentData, 'orders.discount', $request->discount1, $request->discount2);

            if ($request->id) {
                $eloquentData->where('orders.id', '=', $request->id);
            }

            if ($request->user_id) {
                $eloquentData->where('orders.user_id', '=', $request->user_id);
            }
            if ($request->pay_type) {
                $eloquentData->where('orders.pay_type', '=', $request->pay_type);
            }
            if ($request->status) {
                $eloquentData->where('orders.status', '=', $request->status);
            }


            if ($request->downloadExcel == "true") {
                if (staffCan('download.merchant-order.excel')) {
                    $excelData = $eloquentData;
                    $excelData = $excelData->get();
                    exportXLS(__('Merchant Order'),
                        [
                            __('ID'),
                            __('Branch Name'),
                            __('Total'),
                            __('Is Paid'),
                            __('Merchant Name'),
                            __('#Order Item'),
                            __('Created At'),
                        ],
                        $excelData,
                        [
                            'id' => 'id',
                            'branch_name' => 'branch_name',
                            'total' => 'total',
                            'is_paid' => 'is_paid',
                            'merchant_name' => function ($data) {
                                return $data->merchant_name;
                            },
                            'count_order_items' => 'count_order_items',
                            'created_at' => function ($data) {
                                return $data->created_at->format('Y-m-d h:i A');
                            },
                        ]
                    );
                }
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id', '{{$id}}')
                ->addColumn('merchant_name', function ($data) {
                    return '<a target="_blank" href="' . route('merchant.merchant.show', $data->merchant_id) . '">' . $data->merchant_name . '</a>
                            (<a target="_blank" href="' . route('merchant.branch.show', $data->branch_id) . '">' . $data->branch_name . '</a>)';
                })
                ->addColumn('Order Products', '{{$count_order_products}}')
                ->addColumn('total_price', function ($data) {
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>' . __('Pay Type') . '</td>
                                        <td>' . $data->pay_type . '</td>
                                    </tr> 
                                     <tr>
                                        <td>' . __('Price') . '</td>
                                        <td>' . amount($data->price, true) . '</td>
                                    </tr>
                                    <tr>
                                        <td>' . __('Tax') . '</td>
                                        <td>' . amount($data->tax, true) . '</td>
                                    </tr>
                                    <tr>
                                        <td>' . __('Delivery') . '</td>
                                        <td>' . amount($data->delivery, true) . '</td>
                                    </tr> 
                                    <tr>
                                        <td>' . __('Discount') . '</td>
                                        <td>' . amount($data->discount, true) . '</td>
                                    </tr>
                                      <tr>
                                        <td>' . __('Total price') . '</td>
                                        <td>' . amount($data->total_price, true) . '</td>
                                    </tr>
                                </tbody>
                            </table>';

                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action', function ($data) {
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"" . route('merchant.order.show', $data->id) . "\">" . __('View') . "</a></li>
                                <li class=\"dropdown-item\"><a href=\"" . route('merchant.order.edit', $data->id) . "\">" . __('Edit') . "</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('" . route('merchant.order.destroy', $data->id) . "')\" href=\"javascript:void(0)\">" . __('Delete') . "</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 'cancelled') {
                        return 'tr-danger';
                    }
                })
                ->make(true);
        } else {

            // View Data

            $this->viewData['tableColumns'] = [
                'ID',
                'Merchant/Branch',
                'Products',
                'Financial',
                'Created At',
                'Action'];
            if ($request->withTrashed) {

                $this->viewData['pageTitle'] = __('Deleted Merchant Order');
            } else {
                $this->viewData['pageTitle'] = __('Merchant Orders');
            }

            $this->viewData['breadcrumb'][] = [
                'text' => __('Merchant Order'),
            ];

            return $this->view('merchant.order.index', $this->viewData);
        }
    }


    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Deals'),
            'url' => route('system.deal.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Deal'),
        ];

        $this->viewData['pageTitle'] = __('Create Deal');

        return $this->view('deal.create', $this->viewData);
    }


    public function store(Request $request)
    {
        $RequestData = $request->only(['merchant_id', 'merchant_branch_id', 'user_id', 'product', 'discount', 'coupon', 'product','user_address_id','delivery']);

        // $merchant = Merchant::Active()->findOrfail($RequestData['merchant_id']);
        $validator = Validator::make($RequestData, [
            'merchant_id' => 'required|integer|exists:merchants,id',
            'merchant_branch_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'product' => 'required|array'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $user = User::find($RequestData['user_id']);

        $order = new OrderData();
        $order::setLanguage();
        $order::setMerchantBranch($RequestData['merchant_branch_id']);
        $order::setCoupon($user, $RequestData['coupon']);


        foreach ($RequestData['product'] as $key => $row) {
            $addProduct = $order::addProduct($row['id'], $row['quantity'], \DataLanguage::get(), $row['option']);
            if ($addProduct['status'] == false) {
                $errors['errors']['msg'][] = $addProduct['msg'];
                $errors['errors']['product_id'][] = $addProduct['msg'];
            }
        }

        if (!empty($errors['errors']['product'])) {
            return [
                'status' => false,
                'msg' => implode("\n", $errors['errors']['product']), // 'can not add order' ,
                'data' => $errors
            ];
        }
        $addOrder = $order::makeOrder(
            $user,
            'one',
            $RequestData['delivery'],
            $RequestData['discount'],
            \DataLanguage::get(),
            $RequestData['user_address_id']
        );

        if ($addOrder['status'] == false) {
            return [
                'status' => false,
                'msg' => implode("\n", $errors['errors']['product']), // 'can not add order' ,
                'data' => $errors
            ];
        } else
            return $addOrder;

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


    public function show(Deal $deal, Request $request)
    {
        // dd($order->histories->toArray());
        // dd($order->orderProducts->toArray());
        //dd( $order->merchant_branch()->toArray());

        $this->viewData['breadcrumb'][] = [
            'text' => __('Deal'),
            'url' => route('system.deal.index')
        ];
        
        $this->viewData['pageTitle'] = __('Deal');
        $this->viewData['result'] = $deal;
      //  $this->viewData['users'] = User::where('id','!=',$order->user_id)->get(['id','firstname','lastname']);
       // $this->viewData['users_addresses'] = $order->user->addresses;

        return $this->view('deal.show', $this->viewData);
    }

    public function edit(Order $order)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Deals'),
            'url' => url('system.deal.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit Deal'),
        ];

        $this->viewData['pageTitle'] = __('Edit Deal');


        $this->viewData['order'] = $order;

       // $this->viewData['users'] = User::where('id','!=',$order->user_id)->get(['id',DB::raw("CONCAT(firstname,' ',lastname) as name")]);



        return $this->view('merchant.order.create', $this->viewData);
    }


    public function update(Order $order,Request $request){

        //$RequestData = $request->only(['merchant_branch_id', 'user_id', 'pay_type','delivery','discount','user_address_id']);
        $RequestData = $request->only(['name', 'value']);

        $orderLib = new OrderData();
        return $orderLib->updateOrder($order->id,[$RequestData['name']=>$RequestData['value']]);

    }

    public function destroy(Deal $deal , Request $request)
    {
        $deal->delete();
        if ($request->ajax()) {
            return ['status' => true, 'msg' => __('Deal  has been deleted successfully')];
        } else {
            redirect()
                ->route('merchant.order.index')
                ->with('status', 'success')
                ->with('msg', __('This Deal has been deleted'));
        }
    }



    public function productOption(Request $request)
    {
        if (empty($request->item_id))
            return ['status' => true, 'data' => __('No Item Selected')];
        $item = Item::findOrFail($request->item_id);
        if (!empty($item->option->toArray())) {
            return ['status' => true, 'data' => ['options' => $item->option()->where('status', 'active')->orderBy('sort')
                ->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->with(['values' => function ($q) {
                    $q->where('status', 'active');
                }])->get(),
                'price' => $item->price]];
        } else {
            return ['status' => false, 'data' => ['options' => [], 'price' => $item->price]];
        }
    }

    public function checkProductOption(Request $request)
    {
        if (empty($request->item_id))
            return ['status' => false, 'data' => [], 'msg' => 'please select product'];
        $product = MerchantProduct::select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('id', $request->item_id)
            ->with(['option' => function ($q) {
                $q->select(['*', 'name_' . \DataLanguage::get() . ' as name'])->where('status', 'active')->orderBy('sort');
            }, 'option.values' => function ($q) {
                $q->where('status', 'active');
            }])->first();

        $options = [];
        $errors = [];
        if ($product->option) {
            foreach ($product->option as $key => $row) {
                if ($row->is_required == 'yes' && empty($request->option[$row->id])) {
                    $errors[] = $row->id;
                    $errorMsg[] = __('Requied');
                } elseif ($row->is_required == 'no' && empty($request->option[$row->id])) {
                    continue;
                } else if ($row->type == 'check' && ($row->min_select > count($request->option[$row->id]) || $row->max_select < count($request->option[$row->id]))) {
                    $errors[] = $row->id;
                    $errorMsg[] = __('Select from ' . $row->min_select . ' To ' . $row->max_select . ' Values');
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
        $taxes = Tax::select(['id', 'name_' . \DataLanguage::get() . ' as name', 'rate', 'type'])
            ->whereIn('id', explode(',', $product->tax_ids))->get();
        foreach ($taxes as $key => $row) {
            if ($row->type == 'percentage')
                $taxes[$key]['price'] = $product->price / 100 * $row->rate;
            else
                $taxes[$key]['price'] = $row->rate;
        }

        $data = [
            'name' => $product->name,
            'product_id' => $product->id,
            'price' => $product->price,
            'taxes' => $taxes,
            'option' => $options
        ];
        if ($product->upload()->first())
            $data['img'] = asset('storage/' . $product->upload()->first()->path);
        return ['status' => true, 'data' => $data];
    }

    public function invoice(Request $request)
    {
        $RequestData = $request->only(['merchant_id', 'merchant_branch_id', 'user_id', 'product', 'discount', 'coupon', 'product']);

        // $merchant = Merchant::Active()->findOrfail($RequestData['merchant_id']);
        $validator = Validator::make($RequestData, [
            'merchant_id' => 'required|integer|exists:merchants,id',
            'merchant_branch_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'product' => 'required|array'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $data = $request->all();

        $merchant = Merchant::find($data['merchant_id']);
        $merchant_branch = $merchant->merchant_branch()->find($data['merchant_branch_id']);
        $user = User::find($data['user_id']);
        $user_address = $user->addresses()->find($data['user_address_id']);
        $sub_total = 0;
        $total_discount = $data['discount'];
        $data['merchant'] = $merchant;
        $data['merchant_branch'] = $merchant_branch;
        $data['user'] = $user;
        $data['user_address'] = $user_address;
        $data['total_taxes'] = 0;

        foreach ($data['product'] as $product_id => $row) {
            $product = $merchant->products()->find($product_id);
            $options = [];
            $products[$product_id]['option_price'] = 0;
            if ($row['option']) {
                foreach ($row['option'] as $option_id => $value) {
                    $option = $product->option()->find($option_id);
                    if ($option->type == 'text' || $option->type == 'textarea') {
                        $option_value = $value;
                    } else if ($option->type == 'select' || $option->type == 'radio') {
                        $option_value = $option->values()->find($value);
                        $sub_total += $option_value->price * $row['quantity'];
                        $products[$product_id]['option_price'] += $option_value->price;
                    } else if ($option->type == 'check') {
                        $option_value = $option->values()->whereIn('id', $value)->get();
                        foreach ($option_value as $onVal) {
                            $sub_total += $onVal->price * $row['quantity'];
                            $products[$product_id]['option_price'] += $onVal->price;
                        }
                    }
                    $options[$option_id]['option'] = $option;
                    $options[$option_id]['value'] = $option_value;
                }
            }
            $products[$product_id]['info'] = $product;
            $products[$product_id]['price'] = $row['price'];
            $sub_total += $row['price'] * $row['quantity'];
            $products[$product_id]['quantity'] = $row['quantity'];
            $products[$product_id]['option'] = $options;
            $products[$product_id]['taxes'] = 0;
            foreach ($product->taxes() as $tax) {
                if ($tax->type == 'fixed') {
                    $products[$product_id]['taxes'] += $tax->rate;
                    //   $sub_total += $row['quantity'] * $tax->rate;
                    $data['total_taxes'] += $row['quantity'] * $tax->rate;
                } else {
                    $rate = ($row['price'] + $products[$product_id]['option_price'] ) / 100 * $tax->rate;
                    $products[$product_id]['taxes'] += $rate;
                    //  $sub_total += $rate * $row['quantity'];
                    $data['total_taxes'] += $row['quantity'] * $rate;
                }
            }
        }

        $order = new OrderData();
        $order::setMerchantBranch($request->merchant_branch_id);
        $check_coupon = $order::setCoupon($user, $request->coupon);

        if ($check_coupon['status'] == false)
            $coupon = '';
        else {
            $coupon = $check_coupon['coupon'];
            if ($coupon->price_type == 'fixed')
                $total_discount += $coupon->discount;
            else
                $total_discount += ($sub_total / 100) * $coupon->discount;

            $this->viewData['coupon'] = $coupon;
        }


        $data['total_discount'] = $total_discount;
        $data['sub_total'] = $sub_total;
        $this->viewData['data'] = $data;
        $this->viewData['products'] = $products;

        return ['status' => true, 'view' => $this->view('merchant.order.invoice', $this->viewData)->render()];

    }
}