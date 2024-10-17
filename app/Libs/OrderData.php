<?php

namespace App\Libs;

use App\Models\Coupon;
use App\Models\MerchantBranch;
use App\Models\MerchantProduct;
use App\Models\MerchantProductOption;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\OrderProductOption;
use App\Models\User;
use App\Models\UsersAddress;
use App\Models\WalletTransaction;
use DB;
use Illuminate\Support\Facades\Auth;

class OrderData{

    private static $products,$coupon,$merchantBranch,$Language,$merchantStaff;

    private static function response($status,$msg,$data = []){
        return [
            'status'=> $status,
            'msg'   => $msg,
            'data'  => $data
        ];
    }

    public static function setMerchantBranch($branchID){
        $branch = MerchantBranch::join('merchants','merchants.id','=','merchant_branches.merchant_id')
            ->where([
                ['merchant_branches.id',$branchID],
                ['merchant_branches.status','active'],
                ['merchants.status','active']
            ])
            ->with('merchant')
            ->first(['merchant_branches.*']);

        if($branch){
            self::$merchantBranch = $branch;
            return true;
        }

        return false;

    }
    public static function getMerchantBranch(){
        if(self::$merchantBranch){
            return self::$merchantBranch;
        }

        return false;
    }
    public static function getMerchant(){
        if(self::$merchantBranch){
            return self::$merchantBranch->merchant;
        }

        return false;
    }

    public static function setMerchantStaff($merchantStaff)
    {
        if($merchantStaff){
            self::$merchantStaff = $merchantStaff;
            return true;
        }

        return false;

    }
    public static function getMerchantStaff(){
        if(self::$merchantStaff){
            return self::$merchantStaff;
        }

        return false;
    }

    public static function getProduct($productID,$merchantID){
        return MerchantProduct::join('merchants','merchants.id','=','merchant_products.merchant_id')
            ->where('merchants.id',$merchantID)
            ->where('merchant_products.status','active')
            ->where('merchants.status','active')
            ->whereNotNull('merchant_products.approved_at')
            ->where('merchant_products.id',$productID)
            ->with('option.activeValues')
            ->select('merchant_products.*','merchant_products.name_'.self::$Language.' as name')
            ->first();
    }

    public static function addProduct($productID,$quantity,$language,array $option = [],$appendToMainVar = true){
        $merchant = self::getMerchant();
        if(!$merchant){
            return self::response(false,__('Please select merchant'));
        }

        $product = self::getProduct($productID,$merchant->id);

        if(!$product){
            return self::response(false,__('Sorry we can\'t find your product'));
        }

        if($product->quantity == 0){
            return self::response(false,__($product->name.' is currently unavailable'));
        }

        // ----- Option Processing
        $optionErrors  = [];
        $optionSuccess = [];

        $optionPrice = 0;

        if($product->option->isNotEmpty()){
            foreach ($product->option as $key => $value){

                if(isset($option[$value->id])){
                    $optionValue = $option[$value->id];
                }else{
                    $optionValue = '';
                }

                switch ($value->type){
                    case 'text':
                    case 'textarea':
                        if($value->is_required == 'yes' && empty($optionValue)){
                            $optionErrors[$value->id] = __(':name is required',['name'=> $value->{'name_'.$language}]);
                        }

                        if(!empty($value)){
                            $optionSuccess[] = ['id'=>$value->id,'type'=> $value->type,'value'=> $optionValue];
                        }
                        break;
                    case 'select':
                    case 'radio':
                        if($value->is_required == 'yes' && $optionValue == ''){
                            $optionErrors[$value->id] = __(':name is required',['name'=> $value->{'name_'.$language}]);
                        }elseif($value->is_required == 'no' && $optionValue == ''){
                            continue;
                        }

                        $selectValues = $value->activeValues->where('id',$optionValue)->first();

                        if($selectValues){

                            if($selectValues->price_prefix == '+'){
                                $optionPrice+= $selectValues->price;
                            }else{
                                $optionPrice-= $selectValues->price;
                            }

                            $optionSuccess[] = [
                                'id'=>$value->id,
                                'type'=> $value->type,
                                'value'=> [
                                    'id'=> $selectValues->id,
                                    'name'=> $selectValues->{'name_'.$language},
                                    'price_prefix'=> $selectValues->price_prefix,
                                    'price'=> $selectValues->price
                                ]
                            ];
                        }else{
                            $optionErrors[$value->id] = __(':name is required',['name'=> $value->{'name_'.$language}]);
                        }

                        break;
                    case 'check':
                        if($value->is_required == 'yes' && empty($optionValue)){
                            $optionErrors[$value->id] = __(':name is required',['name'=> $value->{'name_'.$language}]);
                        }elseif(!is_array($optionValue)){
                            $optionErrors[$value->id] = __(':name contains invalid value',['name'=> $value->{'name_'.$language}]);
                        }

                        $selectedOptions = [];
                        foreach ($optionValue as $kOption => $vOption){
                            $checkSelectedOption = $selectValues = $value->activeValues->where('id',$vOption)->first();
                            if($checkSelectedOption){

                                if($checkSelectedOption->price_prefix == '+'){
                                    $optionPrice+= $checkSelectedOption->price;
                                }else{
                                    $optionPrice-= $checkSelectedOption->price;
                                }

                                $selectedOptions[] = [
                                    'id'=> $checkSelectedOption->id,
                                    'name'=> $checkSelectedOption->{'name_'.$language},
                                    'price_prefix'=> $checkSelectedOption->price_prefix,
                                    'price'=> $checkSelectedOption->price
                                ];
                            }
                        }

                        $countSelectedOptions = count($selectedOptions);

                        if($countSelectedOptions >= $value->min_select && $countSelectedOptions <= $value->max_select){
                            $optionSuccess[] = ['id'=>$value->id,'type'=> $value->type,'value'=> $countSelectedOptions];
                        }else{
                            $optionErrors[] = __(':name should contains :count values',['name'=> $value->{'name_'.$language},'count'=> $countSelectedOptions]);
                        }

                        break;


                }

            }

        }
        if(count($optionErrors)){
            return self::response(false,__('Validation Error'),$optionErrors);
        }
        // ----- Option Processing

        // ----- Taxes
        $tax        = 0;
        $productTax = $product->taxes();
        if($productTax->isNotEmpty()){
            foreach ($productTax as $oneProductTax){
                if($oneProductTax->type == 'fixed'){
                    $tax += $oneProductTax->rate;
                }else{
                    $tax += (($product->price+$optionPrice)*$oneProductTax->rate)/100;
                }
            }
        }
        // ----- Taxes

        // --- Start push product to array

        if(!$appendToMainVar){
            return self::response(true,__('Done'),[
                'products'=> collect([
                    // -- Product
                    'id'                => $productID,
                    'name'              => $product->{'name_'.$language},
                    'price'             => $product->price,
                    'option_price'      => $optionPrice,
                    'tax_price'         => $tax,
                    'price_with_option' => $product->price+$optionPrice,
                    'total_price'       => ($product->price+$optionPrice+$tax)*$quantity,
                    'tax_ids'           => $product->tax_ids,
                    'taxes'             => $productTax->toArray(),
                    'quantity'          => $quantity,
                    'option'            => $option,
                    'fingerPrint'       => false
                ])
            ]);
        }

        if(self::$products == null){
            self::$products = collect();
        }

        $fingerPrint   = md5(json_encode($optionSuccess));
        $existsProduct = self::$products->where('id',$productID)->first();

        if($existsProduct && $existsProduct['fingerPrint'] == $fingerPrint){

            $updatedProducts = [];
            foreach (self::$products as $key => $value){
                if($value['id'] == $productID){
                    $value['total_price']   += ($product->price+$optionPrice+$tax)*$quantity;
                    $value['quantity']      += $quantity;
                }
                $updatedProducts[] = $value;
            }
            self::$products = collect($updatedProducts);
        }else{
            self::$products->push([
                // -- Product
                'id'            => $productID,
                'name'          => $product->{'name_'.$language},
                'price'         => $product->price,
                'option_price'  => $optionPrice,
                'tax_price'     => $tax,
                'price_with_option' => $product->price+$optionPrice,
                'total_price'   => ($product->price+$optionPrice+$tax)*$quantity,
                'tax_ids'       => $product->tax_ids,
                'taxes'         => $productTax->toArray(),
                'quantity'      => $quantity,
                'option'        => $option,
                'fingerPrint'   => $fingerPrint,
            ]);
        }

        return self::response(true,__('Done'),[
            'products'=> self::$products
        ]);
    }


    /**
     * @param $key
     * @param $quantity
     * @param string $type set|plus|minus
     */
    public static function updateProductQuantity($key, $quantity, $type = 'plus'){
        if(is_object(self::$products)){
            $data = self::$products->toArray();
        }else{
            $data = self::$products;
        }

        if(!isset($data[$key])){
            return false;
        }

        if($type == 'plus' || $type == '+'){
            $data[$key]['quantity']+= $quantity;
        }elseif($type == 'minus' || $type == '-'){
            $data[$key]['quantity']-= $quantity;
        }else{
            $data[$key]['quantity'] = $quantity;
        }

        if($data[$key]['quantity'] < 1){
            return self::deleteProduct($key);
        }else{
            self::$products = collect($data);
        }

        return true;

    }

    public static function deleteProduct($key){

        if(is_object(self::$products)){
            $data = self::$products->toArray();
        }else{
            $data = self::$products;
        }

        if(!isset($data[$key])){
            return false;
        }

        unset($data[$key]);
        self::$products = collect($data);

        return true;

    }






    public static function checkCoupon($user,$code){

        $merchant = self::getMerchant();
        if(!$merchant){
            return self::response(false,__('Please select merchant'));
        }

        $coupon = Coupon::where('code',$code)->first();

        $date   = date('Y-m-d');

        // Check
        if(!$coupon){
            return [
                'status'=> false,
                'msg'=> __('There are no coupon with this code :code',['code'=>$code])
            ];
        }elseif($coupon->status != 'active') {
            return [
                'status'=> false,
                'msg'=> __('this coupon has been deactivated')
            ];
        }elseif (
            (strtotime($date) <= strtotime($coupon->start_date) || strtotime($date) >= strtotime($coupon->end_date)) ||
            ($coupon->number_of_uses >= $coupon->uses_total)
        ){
            return [
                'status'=> false,
                'msg'=> __('this coupon has been expired')
            ];
        }elseif($coupon->merchant_ids !== null && !in_array($merchant->id,explode(',',$coupon->merchant_ids))){
            return [
                'status'=> false,
                'msg'=> __('There are no coupon with this code :code',['code'=>$code])
            ];
        }


        // Number Of uses for each user

        $getOrdersPerUser = Order::where('user_id',$user->id)
            ->where('coupon_id',$coupon->id)
            ->count();

        if($getOrdersPerUser >= $coupon->uses_customer){
            return [
                'status'=> false,
                'msg'=> __('you can\'t use this coupon more than :number times',['number'=> $coupon->uses_customer])
            ];
        }


        return [
            'status'=> true,
            'coupon'=> $coupon,
            'msg'=> __('Coupon set successfully')
        ];
    }

    public static function setLanguage($lang = '' ){
        if(!empty($lang ) ){
            self::$Language = $lang;
        }else {
            self::$Language = \DataLanguage::get();
        }
    }

    public static function setCoupon($user,$code){

        $coupon = self::checkCoupon($user,$code);

        if($coupon['status']){
            self::$coupon = $coupon['coupon'];
            return [
                'status'=> true,
                'msg'=> __('Coupon set successfully'),
                'coupon'=>self::$coupon
            ];
        }

        return $coupon;
    }

    public static function getData(){

        if(!is_object(self::$products)){
            return [
                'status'=> false,
                'msg'   => __('You do not have products in the cart')
            ];
        }


        // Product Array
        $productArray = self::$products->toArray();

        $couponDiscount = 0;

        // Discount for products
        $couponDiscountForProducts = [];
        if(
            self::$coupon != null &&
            in_array(self::$coupon->discount_for,['first_product','all_products'])
        ){

            foreach ($productArray as $key => $value){
                if( in_array($value['id'],explode(',',self::$coupon->product_ids)) ){
                    if(self::$coupon->discount_for == 'first_product'){
                        $couponDiscountForProducts[$key] += self::calculateCouponDiscount($value['price'],self::$coupon->price_type,self::$coupon->discount);
                        break;
                    }
                }
            }

            $couponDiscount += array_sum($couponDiscountForProducts);

        }
        // End Discount for products


        // Discount for total order
        if(
            self::$coupon != null
            && self::$coupon->discount_for == 'total_order'
        ){
            $productArray  = array_sum(array_column($productArray,'price'));
            $couponDiscount += self::calculateCouponDiscount($productArray,self::$coupon->price_type,self::$coupon->discount);
        }
        // End Discount for total order

        return self::response(true,'Done',[
            'products'          => $productArray,
            'coupon_discount'   => $couponDiscount,
            'total_amount'      => array_sum(array_column($productArray,'total_price')),
            'total_tax'         => array_sum(array_column($productArray,'tax_price')),
            'count_products'    => count($productArray)
        ]);

    }



    /**
     * @param $createBy ['merchant_staff','staff','user']
     * @param $user
     * @param $language
     * @return array
     */
    public static function makeOrder(
        $user,
        $payType,
        $deliveryPrice,
        $discount,
        $language,
        $user_address_id,
        $merchantStaff = null
    ){
        $data = self::getData($language);

        if(!$data['status']){
            return $data;
        }

        // Merchant Data
        $merchant       = self::getMerchant();
        $merchantBranch = self::getMerchantBranch();
        // $merchantStaff  = self::getMerchantStaff();
        $data           = $data['data'];

        if(!$merchant){
            return [
                'status'=> false,
                'msg'=> __('You should select merchant'),
                'data'=> []
            ];
        }


        $orderData = [
            'merchant_branch_id'    => $merchantBranch->id,
            'pay_type'              => $payType,
            'status'                => 'pending',
            'price'                 => $data['total_amount'],
            'tax'                   => $data['total_tax'],
            'delivery'              => $deliveryPrice,
            'discount'              => $discount,
            'coupon_discount'       => $data['coupon_discount'],
            'total_price'           => ($data['total_amount']+$deliveryPrice)-($discount),
            'creatable_id'          => Auth::id(),
            'creatable_type'        => Auth::user()->modelPath,
        ];


        if(!empty($user)){
            $orderData['user_id'] = $user->id;
            $orderData['user_address_id'] = $user_address_id;
        }

        if(!empty($merchantStaff))
            $orderData['merchant_staff_id'] = $merchantStaff->id;


        if(self::$coupon != null){
            $orderData['coupon_id'] = self::$coupon->id;
        }

        $checkCreateOrder = DB::transaction(function () use ($orderData,$user,$data) {
            // Create Order
            $order = Order::create($orderData);
            // Order History
            $orderHistory = OrderHistory::create([
                'order_id' => $order->id,
                'order_history_status_id' => setting('create_order_history_status_id'),
                'creatable_type' => $user->modelPath,
                'creatable_id' => $user->id
            ]);

            $order->update(['order_history_id' => $orderHistory->id]);

            // Add Product
            foreach ($data['products'] as $key => $value) {

                // Add Product to database
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $value['id'],
                    'quantity' => $value['quantity'],
                    'price' => $value['price'],
                    'option_price' => $value['option_price'],
                    'tax_price' => $value['tax_price'],
                    'tax_ids' => $value['tax_ids'],
                    'tax_info' => $value['taxes'],
                    'total_price' => $value['total_price']
                ]);

                if (!empty($value['options']) && is_array($value['options'])) {
                    foreach ($value['options'] as $kOption => $vOption) {
                        $orderProductOption = [
                            'order_product_id' => $orderProduct->id,
                            'option_id' => $vOption['id']
                        ];

                        if (is_array($vOption['value'])) {
                            $orderProductOption['prefix_price'] = $vOption['value']['price_prefix'];
                            $orderProductOption['price'] = $vOption['value']['price'];
                            $orderProductOption['option_value_id'] = $vOption['value']['id'];
                        } else {
                            $orderProductOption['option_value'] = $vOption['value'];
                        }

                        // Add product option to database
                        OrderProductOption::create($orderProductOption);
                    }
                }
            }

            return [
                'status'=>true,
                'msg'=>__('order added'),
                'data'=>$order
            ];

        });

        return $checkCreateOrder;
    }



    // Get Order
    // Update Order
    // make fund
    // request payment
    public static function getOrder($orderID){
        $order = Order::find($orderID);
        if(!$order){
            return false;
        }
        return $order;
    }


    /**
     * @param $orderID
     * @param array $orderUpdates
     * @NOTE to update coupon you should pass coupon_code not coupon_id
     * @return array
     */

    public static function updateOrder($orderID, array $orderUpdates){
        $order = Order::find($orderID);

        if(!$order){
            return [
                'status'=> false,
                'msg'=> __('There are no order with this ID: :id',['id'=>$orderID]),
                'data'=> []
            ];
        }

        // Plus and minus
        $plusAmount  = 0;
        $minusAmount = 0;
        // Plus and minus

        $newOrderUpdates = [];
        $newOrderUpdatesErrors = [];
        foreach ($orderUpdates as $key => $value){
            switch ($key){
                case 'merchant_branch_id':
                    $branch = MerchantBranch::join('merchants','merchants.id','=','merchant_branches.merchant_id')
                        ->where([
                            ['merchant_branches.id',$value],
                            ['merchant_branches.status','active'],
                            ['merchants.status','active']
                        ])
                        ->with('merchant')
                        ->first(['merchant_branches.*']);

                    if($branch){
                        $newOrderUpdates['merchant_branch_id'] = $value;
                    }else{
                        $newOrderUpdatesErrors['merchant_branch_id'] = __('There are no merchant branch with this #ID: :id',['id'=>$value]);
                    }

                    break;


                case 'user_id':
                    $user = User::where([
                        ['id',$value],
                        ['status','active']
                    ])->first();

                    if($user){
                        $newOrderUpdates['user_id'] = $value;
                        $user_address = $user->addresses()->where('is_default','yes')->first();
                        if(!empty($user_address)) {
                            $newOrderUpdates['user_address_id'] = $user_address->id;
                        } else{
                             $newOrderUpdates['user_address_id'] =$user->addresses()->first()->id;
                         }

                    }else{
                        $newOrderUpdatesErrors['user_id'] = __('There are no user with this #ID: :id',['id'=>$value]);
                    }

                    break;


                case 'pay_type':
                    if(in_array($value,['multi', 'one'])){
                        $newOrderUpdates['pay_type'] = $value;
                    }else{
                        $newOrderUpdatesErrors['pay_type'] = __('Pay type should be one or multi');
                    }

                    break;


                case 'delivery':
                    $newOrderUpdates['delivery'] = $value;
                    $plusAmount  += $value-$order->delivery;
                    break;


                case 'discount':
                    $newOrderUpdates['discount'] = $value;
                    $minusAmount += $value-$order->discount;
                    break;

                case 'user_address_id':

                    $address = UsersAddress::where([
                        ['id',$value],
                        ['user_id',(isset($orderUpdates['user_id'])) ? $orderUpdates['user_id']: $order->user_id]
                    ])
                        ->first();

                    if($address){
                        $newOrderUpdates['user_address_id'] = $value;
                    }else{
                        $newOrderUpdatesErrors['user_address_id'] = __('There are no address with this ID: :id',['id'=>$value]);
                    }

                    break;


//                case 'coupon_code':
//
//                    if(isset($orderUpdates['user_id'])){
//                        $userData = User::findOrFail($orderUpdates['user_id']);
//                    }else{
//                        $userData = User::findOrFail($order->user_id);
//                    }
//
//
//                    $checkCoupon = self::checkCoupon($userData,$value);
//
//                    if($checkCoupon['status']){
//                        $newOrderUpdates['coupon_id'] = $checkCoupon['coupon']->id;
//                        $plusAmount+=
//                    }else{
//                        $newOrderUpdatesErrors['user_address_id'] = $checkCoupon['msg'];
//                    }
//
//                    break;


            }
        }

        if(count($newOrderUpdatesErrors)){
            return [
                'status'    => false,
                'msg'       => __('Validation Error'),
                'data'    => $newOrderUpdatesErrors
            ];
        }elseif(!count($newOrderUpdates)){
            return [
                'status'    => false,
                'msg'       => __('There are no data to update'),
                'data'      => []
            ];
        }

        $newOrderUpdates['total_price'] = ($order->total_price-$minusAmount)+$plusAmount;

        $order->update($newOrderUpdates);

        return [
            'status'=> true,
            'msg'=>__('Order updated successfully')
        ];
    }

    public static function addProductToOrder($orderID,$productID,$quantity,$language,array $option = []){
        $order = Order::find($orderID);
        if(!$order){
            return [
                'status'=> false,
                'msg'=> __('There are no order with this ID: :id',['id'=>$orderID]),
                'data'=> []
            ];
        }

        // Check if same product is exists with same options
        $checkSameProduct = self::checkSameProduct($order,$productID,$option);
        if($checkSameProduct['status'] === true){
            return self::updateOrderProductQuantity($checkSameProduct['data']['order_product_id'],$quantity);
        }

        $productData = self::addProduct($productID,$quantity,$language,$option,false);

        if(!$productData['status']){
            return $productData;
        }


        $product = $productData['data']['products'];

        $checkAppendProductsToOrder = DB::transaction(function () use ($quantity, $order,$product) {

            // Add Product
            $orderProduct = OrderProduct::create([
                'order_id'      => $order->id,
                'product_id'    => $product['id'],
                'quantity'      => $product['quantity'],
                'price'         => $product['price'],
                'option_price'  => $product['option_price'],
                'tax_ids'       => $product['tax_ids'],
                'tax_info'      => $product['taxes'],
                'total_price'   => $product['price']
            ]);

            if (!empty($product['options']) && is_array($product['options'])) {
                foreach ($product['options'] as $kOption => $vOption) {
                    $orderProductOption = [
                        'order_product_id' => $orderProduct->id,
                        'option_id' => $vOption['id']
                    ];

                    if (is_array($vOption['value'])) {
                        $orderProductOption['prefix_price'] = $vOption['value']['price_prefix'];
                        $orderProductOption['price'] = $vOption['value']['price'];
                        $orderProductOption['option_value_id'] = $vOption['value']['id'];
                    } else {
                        $orderProductOption['option_value'] = $vOption['value'];
                    }

                    // Add product option to database
                    OrderProductOption::create($orderProductOption);
                }
            }

            $order->increment('price',$product['price_with_option']);
            $order->increment('tax',$product['tax_price']);
            $order->increment('total_price',$product['price']);

            return true;
        });


        if($checkAppendProductsToOrder){
            return [
                'status'=> true,
                'msg'=> __('Product has been added successfully'),
                'data'=> []
            ];
        }else{
            return [
                'status'=> false,
                'msg'=> __('Unable to add product to this order'),
                'data'=> []
            ];
        }

    }

    private static function checkSameProduct(Order $order,$productID,array $option = []){
        if(!$order){
            return [
                'status'=> false,
                'msg'=> __('Sorry we can\'t check this order'),
                'data'=> []
            ];
        }

        $getSameProducts = $order
            ->products()
            ->where('product_id',$productID)
            ->with('options')
            ->get();


        if($getSameProducts->isNotEmpty()){
            foreach ($getSameProducts as $key => $value){
                $checkOptionsSuccess = 0;
                foreach ($value->options as $sameProductOption){
                    if(
                        array_key_exists($sameProductOption->option_id,$option)
                        && ($sameProductOption->option_value_id == $option[$sameProductOption->option_id] || $sameProductOption->option_value == $option[$sameProductOption->option_id])
                    ){
                        $checkOptionsSuccess++;
                    }else{
                        continue;
                    }
                }

                if($checkOptionsSuccess == count($option)){
                    return [
                        'status'=> true,
                        'msg'=> __('Done'),
                        'data'=> [
                            'order_product_id'=> $value->id,
                        ]
                    ];
                }

            }
        }

        return [
            'status'=> false,
            'msg'=> __('Same product doesn\'t exists'),
            'data'=> []
        ];

    }

    public static function updateOrderProducts($orderProductID,$quantity,$language,array $option = []){
        $orderProduct = OrderProduct::find($orderProductID);
        if(!$orderProduct || $orderProductID->order->status != 'pending'){
            return [
                'status'=> false,
                'msg'=> __('Sorry we can\'t update this product'),
                'data'=> []
            ];
        }


        $addProduct = self::addProduct($orderProduct->product_id,$quantity,$language,$option,false);
        if(!$addProduct['status']){
            return $addProduct;
        }


    }

    private static function calculateCouponDiscount($amount,$priceType,$discount){
        if($priceType == 'fixed'){
            return $amount-$discount;
        }elseif($priceType == 'percentage'){
            return ($amount*$discount)/100;
        }

        return $amount;
    }

}