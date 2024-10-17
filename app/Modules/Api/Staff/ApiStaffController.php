<?php

namespace App\Modules\Api\Staff;

use App\Http\Controllers\Controller;

use App\Libs\Payments\Payments;
use App\Libs\Create;
use App\Libs\WalletData;
use App\Models\Area;
use App\Models\AreaType;

use App\Models\MerchantCategory;
use App\Models\MerchantStaff;
use App\Models\PaymentServices;
use App\Models\RequestBalance;
use App\Models\Staff;
use App\Models\TempData;
use App\Models\PaymentTransactions;
use App\Models\Transactions;
use App\Models\Visits;
use App\Models\WalletTransaction;
use App\Models\Deposits;
use App\Models\Bank;

//use App\Modules\Api\StaffTransformers\Visits as VisitsTransformer;
//use App\Modules\Api\StaffTransformers\DepositTransformer;
//use App\Modules\Api\StaffTransformers\ServiceTransformer;

use App\Modules\Api\Transformers\Transformer;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\File;

use App\Models\Merchant;
use App\Models\MerchantContract;
use App\Models\Upload;
use App\Models\Wallet;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\ValidatorServiceProvider;

use Mockery\Exception;
use phpseclib\Crypt\RC4;
use Yajra\Datatables\Facades\Datatables;
use App\Modules\Api\StaffTransformers\WalletTransformer;
use App\Modules\Api\StaffTransformers\InvoiceTransformer;
use App\Modules\Api\StaffTransformers\OneInvoiceTransformer;
use App\Modules\Api\StaffTransformers\WalletTransactionsTransformer;
use App\Modules\Api\StaffTransformers\StaffTransformer;
use App\Modules\Api\StaffTransformers\TransferTransformer;
use App\Modules\Api\StaffTransformers\User\MerchantTransformer;

use Illuminate\Foundation\Auth\User as Authenticatable;


use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Notification;
use App\Notifications\UserNotification;
use App\Libs\I18N\I18N_Arabic;


use Illuminate\Pagination\LengthAwarePaginator;

class ApiStaffController extends Controller
{
    public $systemLang;
    public $JsonData;
    public $StatusCode = 200;
    public $Code = 200;
    public $lastupdate;
    public $Date = '2018-01-27 12:00:11';
    public $AppVersion = '1.0';

    public function __construct()
    {

//        header("Access-Control-Allow-Origin:*");
//        header("Access-Control-Allow-Credentials: true");
//        header("Access-Control-Allow-Headers: origin, content-type, accept, Set-Cookie");
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//        header('Access-Control-Max-Age: 166400');
        $this->systemLang = 'ar';

//        if(Auth::id()) {
//            if (!staffCan('show-tree-users-data', Auth::id())) {
//                $this->respondWithError([],__('Youd Don\'t have permission to this request'));
//            }
//        }

      $this->middleware('auth:ApiStaff')->except(['login','version','DownloadStaffApk']);

    }

    public function getCode(){
        return $this->Code;
    }


    public function respondWithoutError($data,$type,$message){
        return response()->json([
            'status' => true,
            'type'=>$type,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }

    public function respondWithError($data,$message){
        return response()->json([
            'status' => false,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }



    public function json($status,$msg = '', $data = [], $code = 200)
    {
        echo json_encode( ['status' => $status,'msg' => $msg, 'code' => $code, 'data' => (object)$data]);


    }

    public function login(Request $request)
    {

        $RequestData = $request->only(['email', 'password']);
        $validator = Validator::make($RequestData, [
            'email'     => 'required|exists:staff,email',
            'password'  => 'required'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        try {
            $client = new \GuzzleHttp\Client();
            $client = new \GuzzleHttp\Client();
            $response = $client->post( 'http://e-payment.egpay.com:7443/oauth/token', [
                'form_params' => [
                    'client_id' => 8,//8 local 4
                    // The secret generated when you ran: php artisan passport:install
                    //  'client_secret' => '7HJ0GLLlla7khdYCHk5tbB1qPYqionZfE5FmlRTg',
                    'client_secret' => '7HJ0GLLlla7khdYCHk5tbB1qPYqionZfE5FmlRTg',
                    'grant_type' => 'password',
                    'username' => $RequestData['email'],
                    'password' => $RequestData['password'],
                    'scope' => '*',
                ]
            ]);
            $auth = json_decode((string)$response->getBody());
            if ($auth->access_token) {
                $staff = Staff::select("staff.*")->with('paymentWallet')
                    ->where('email', $RequestData['email'])
                    ->where('status','active')
                    ->first();

                if(!$staff){
                    return $this->json(false,__('invalid Auth'));
                }

                $auth->id = $staff->id;
                $auth->firstname = $staff->firstname;
                $auth->lastname = $staff->lastname;
                $auth->email = $staff->email;
                $auth->balance =$staff->paymentWallet->balance;
                $auth->permission_group_id = $staff->permission_group_id;


                $merchant_category = MerchantCategory::select('id', 'name_' . $this->systemLang . ' as name')
                    ->where('status', '=', 'active')->get();
                $area = $this->AreaChildern(0, 1);
                $contract_papers = setting('contract_papers');
                $data = ['merchant_category' => $merchant_category, 'area' => $area, 'contract_papers' => $contract_papers];
                return $this->json(true, __('login successful'),['auth'=>$auth,'data'=>$data]);
            } else {
                return $this->json(false,__('invalid Auth'));
            }
        } catch (RequestException $e) {
           // dd($e->getMessage());
            return $this->json(false,__('invalid credentials'));
        }

    }

    public function changePassword(Request $request)
    {

        $RequestData = $request->only(['old_password', 'password', 'password_confirmation']);

        $validator = Validator::make($RequestData, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6'

        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }  else {

            $check_user = Staff::select('id', 'password')->where('id', Auth::id())->first();

            if (!empty($check_user) && Hash::check($request->old_password, $check_user->password)) {
                Staff::where('id',Auth::id())->update(['password' => bcrypt($request->password)]);
                // return $this->json(true, __('Password Change successful'));
                return $this->json(true,__('Password Changed Successfully'));
            } else {
                return $this->json(false, __('Wrong Old Password'));
            }
        }
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        $user = Auth::user();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $user->tokens()->where('id', '=', $id)->first()->revoke();

        return $this->json(true,__('Logged out'));
    }

    public function merchants(Request $request)
    {

        if (!staffCan('merchant.merchant.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);

        }

        if (!Auth::user()->is_supervisor()) {    // saller

            $merchants = Merchant::viewData($this->systemLang)
                ->with(['paymentWallet' => function ($query)  {
                    $query->select(['walletowner_id', 'walletowner_type', 'id as wallet_id', 'balance']);
                }])->where(['merchants.status' => 'active', 'merchants.staff_id' => Auth::user()->id])
                ->groupBy('merchants.id');

            if($request->mobile){
                $merchants->where('merchant_staff.mobile', '=',$request->mobile);
            }

            if ($request->id)
                $merchants->where('merchants.id', '=', $request->id);
            if ($request->name)
                $merchants->where('merchants.name_' . $this->systemLang, 'LIKE', '%' . $request->name . '%');

            if ($request->staff_id)
                $merchants->where('merchants.staff_id', '=', $request->staff_id);

            if ($request->category_id)
                $merchants->where('merchants.merchant_category_id', '=', $request->category_id);


            $walletTransformer = new  WalletTransformer;



            $seller_balance = Auth::user()->paymentWallet;
            $seller_balance = ['wallet_id'=>$seller_balance->id,'balance'=>$seller_balance->balance];

            if (empty($merchants->first()))
                return $this->json(false, __('No Merchant Available'));
            else {
                $merchants = $merchants->orderBy('created_at','DESC')->jsonPaginate();

                return $this->json(true, __('Merchants'), ['count_merchants' => count($merchants), 'seller' => $seller_balance, 'merchants' => $walletTransformer->transformCollection($merchants->toArray(), [$this->systemLang], 'merchantWithWallet')]);
            }
        }else {

            $staffOfSupervisor = Staff::select('id')->where('supervisor_id', '=', Auth::user()->id)->get();
            if (!empty($staffOfSupervisor)) {
                $staffsID = [];
                foreach ($staffOfSupervisor as $row) {
                    array_push($staffsID, $row->id);
                }

                $merchants = Merchant::viewData($this->systemLang)
                    ->with(['paymentWallet' => function ($query)  {
                        $query->select(['walletowner_id', 'walletowner_type', 'id as wallet_id', 'balance']);
                    }])->where('merchants.status', '=', 'active')->whereIn('merchants.staff_id', $staffsID)
                    ->groupBy('merchants.id');

                if($request->mobile){
                    $merchants->where('merchant_staff.mobile', '=',$request->mobile);
                }

                if ($request->id)
                    $merchants->where('merchants.id', '=', $request->id);
                if ($request->name)
                    $merchants->where('merchants.name_' . $this->systemLang, 'LIKE', '%' . $request->name . '%');
                if ($request->staff_id)
                    $merchants->where('merchants.staff_id', '=', $request->staff_id);
                if ($request->category_id)
                    $merchants->where('merchants.merchant_category_id', '=', $request->category_id);



                $walletTransformer = new  WalletTransformer;



                $seller_balance = Auth::user()->paymentWallet;
                $seller_balance = ['wallet_id'=>$seller_balance->id,'balance'=>$seller_balance->balance];


                if (empty($merchants->first()))
                    return $this->json(false, __('No Merchant Available'));
                else {
                    $merchants = $merchants->orderBy('created_at','DESC')->jsonPaginate();

                    $merchantCount = count($merchants);

                    return $this->json(true, __('Merchants'), ['count_merchants' => $merchantCount, 'seller' => $seller_balance, 'merchants' => $walletTransformer->transformCollection($merchants->toArray(), [$this->systemLang], 'merchantWithWallet') ]);
                }
            } else {
                return $this->json(false, __('No Sellers To This Supervisor'));
            }
        }



    }

    public function merchantInfo(Request $request)
    {
        if (!staffCan('merchant.merchant.show', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $RequestData = $request->only(['id']);
        $customAttributes = ['id' => 'كود التاجر'];
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:merchants'
        ]);
        $validator->setAttributeNames($customAttributes);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }  else {

            $data = Merchant::where('id',$RequestData['id'])
                ->with('merchant_category')
                ->with('paymentWallet')
                ->with('staff')
                ->with('area')->first();


            $merchantTransform = new MerchantTransformer();
            $data = $merchantTransform->merchantApiTransform($data->toArray(), $this->systemLang);

            if($data)
                return $this->json(true, __('Merchant_info'),$data);
            else
                return $this->json(false,'خطا فى البيانات');
        }


    }

    public function createMerchant()
    {


        if (!staffCan('merchant.merchant.fast-create', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $merchant_category = MerchantCategory::select('id', 'name_' . $this->systemLang . ' as name')
            ->where('status', '=', 'active')->get();
        $area = $this->AreaChildern(0, 1);
        $contract_papers = setting('contract_papers');
        $data = ['merchant_category' => $merchant_category, 'area' => $area, 'contract_papers' => $contract_papers];
        return $this->json(true,__('Data'), $data);


    }

    public function AreaChildern($id, $type_id)
    {

        $area = Area::select('areas.id', 'areas.area_type_id', 'areas.name_' . $this->systemLang . ' as name','area_types.name_' . $this->systemLang . ' as type_name')
            ->join('area_types','areas.area_type_id','=','area_types.id')
            ->where(['areas.parent_id' => $id, 'areas.area_type_id' => $type_id])->get();
        return $area;
    }

    public function getAreaChildern($id, $type_id)
    {
        $type_id +=1;

        $area = Area::select('areas.id', 'areas.area_type_id', 'areas.name_' . $this->systemLang . ' as name','area_types.name_' . $this->systemLang . ' as type_name')
            ->join('area_types','areas.area_type_id','=','area_types.id')
            ->where(['areas.parent_id' => $id, 'areas.area_type_id' => $type_id])->get();
        if (!empty($area))
            return $this->json(true,__('Area'), $area);
        else
            return $this->json(false, 'لا يوجد مناطق لهذا المكان');
    }

    public function transactions(Request $request)
    {
        if (!staffCan('payment.transactions.list', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $RequestData = $request->only(['id']);
        $customAttributes = ['id' => ' كود التاجر'];
        $validator = Validator::make($RequestData, [
            'id' => 'required|exists:merchants'
        ]);

        $validator->setAttributeNames($customAttributes);
        if ($validator->errors()->any()) {

            return $this->ValidationError($validator, __('Validation Error'));
        } else {
            $RequestData['merchant_id'] = $RequestData['id'];

            $eloquentData = Merchant::find($RequestData['merchant_id'])->paymentWallet->allTransaction();


            //Auth::user()->paymentWallet->allTransaction();

            whereBetween($eloquentData, 'created_at', $request->created_at1, $request->created_at2);
            if ($request->status) {
                $eloquentData->where('status', $request->status);
            }

            $eloquentData->where(function ($query) {
                $query->whereNull('model_id')->orWhere('model_type', '=', 'App\\Models\\WalletSettlement');
            });



            if ($request->type) {
                if ($request->type == 'settlement')
                    $eloquentData->where('model_type', '=', 'App\\Models\\WalletSettlement');
                if ($request->type == 'transfer')
                    $eloquentData->whereNull('model_id');
            }

            $rows = $eloquentData->orderBy('created_at', 'DESC')->jsonPaginate();
            if (!$rows->items())
                return $this->json(false, __('No Wallet Transactions to display'));

            $WalletTransactionsTransformer = new WalletTransactionsTransformer();
            return $this->json(true,__('Merchant Transactions details'),
                array_merge(
                    $WalletTransactionsTransformer->transformCollection($rows->toArray(), [$this->systemLang]),
                    ['balance' => Merchant::find($RequestData['merchant_id'])->paymentWallet()->first()->balance . ' ' . __('LE')]
                )
            );

        }

    }

    public function myWalletTransactions(Request $request)
    {
        if (!staffCan('system.wallet.transactions', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }



        $eloquentData = Auth::user()->paymentWallet->allTransaction();

        whereBetween($eloquentData, 'created_at', $request->created_at1, $request->created_at2);
        if ($request->status) {
            $eloquentData->where('status', $request->status);
        }

        $eloquentData->where(function ($query) {
            $query->whereNull('model_id')->orWhere('model_type', '=', 'App\\Models\\WalletSettlement');
        });



//        if ($request->type) {
//            if ($request->type == 'settlement')
//                $eloquentData->where('model_type', '=', 'App\\\Models\\\WalletSettlement');
//            if ($request->type == 'transfer')
//                $eloquentData->whereNull('model_id');
//        }


        $walletTransaction =    $eloquentData->orderBy('created_at','DESC')->jsonPaginate();

         if (!$walletTransaction->items())
            return $this->json(false,'لا توجد تحويلات');
        $walletTransformer = new WalletTransactionsTransformer ();
        // pda($walletTransaction);
        $walletTransaction = $walletTransformer->transformCollection($walletTransaction->toArray(),[$this->systemLang]);
        return $this->json(true,__('Transactions'),$walletTransaction);
    }

    public function OneWalletTransactions(Request $request)
    {
        if (!staffCan('system.wallet.transactions', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $inputs = $request->only('id', 'type','owner_id');
        if($inputs['type'] == 'owner') {
            $inputs['owner_id'] = Auth::id();
            $inputs['type'] = 'staff';
        }

        $validator = Validator::make($inputs, [
            'id' => 'required|numeric|exists:transactions,id',
            'owner_id' => 'required',
            'type' => 'required'

        ]);



        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        if($inputs['type'] == 'merchant') {
            $eloquentData = Merchant::find($inputs['owner_id'])->paymentWallet->allTransaction()
                ->where('transactions.id', '=', $inputs['id'])->first();
            $balance = Merchant::find($inputs['owner_id'])->paymentWallet->balance;
        }
        else{
            $eloquentData = Staff::find($inputs['owner_id'])->paymentWallet->allTransaction()
                ->where('transactions.id', '=', $inputs['id'])->first();
            $balance = Staff::find($inputs['owner_id'])->paymentWallet->balance;
        }

        if (!$eloquentData)
            return $this->json(false, __('No Wallet Transaction to display'),['balance' => $balance . ' ' . __('LE')]);

        if ($eloquentData->model_type == 'App\Models\WalletSettlement') {
            $eloquentData->model->payment_invoice;
            $invoiceIDs = recursiveFind($eloquentData->model->payment_invoice->toARray(), 'id');
            if (count($invoiceIDs)) {
                $serviceList = PaymentTransactions::serviceList($this->systemLang, [])
                    ->whereIn('payment_invoice.id', $invoiceIDs)
                    ->get();
                $eloquentData['model']['payment_invoice']['service_list'] = $serviceList;
                $eloquentData['model']['payment_invoice']['invoiceIDs'] = $invoiceIDs;
            }

        }
        $WalletTransactionsTransformer = new WalletTransactionsTransformer();
        return $this->json(true , __('Merchant Transaction details'),
            array_merge(
                $WalletTransactionsTransformer->OneTransaction($eloquentData->toArray(), $this->systemLang),
                ['balance' => $balance . ' ' . __('LE')]
            )
        );
    }

    public function invoicesFilterData(Request $request){

        if (!staffCan('payment.invoice.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $inputs = $request->only(['id']);
        $validator = Validator::make($inputs, [
            'id' => 'numeric|exists:merchants',
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $merchantStaff = Merchant::find($inputs['id'])->MerchantStaff()->get(['merchant_staff.id','merchant_staff.firstname','merchant_staff.lastname']);

        $MerchatStaffIDS = array_column($merchantStaff->toArray(),'id');
        //$MerchatStaffArray = array_column($merchantStaff->toArray(),'firstname','id');
        $paymentTransactions = PaymentTransactions::where('model_type','App\\Models\\MerchantStaff')
            ->whereIn('model_id',$MerchatStaffIDS)
            ->groupBy('payment_services_id')
            ->get(['payment_services_id']);
        $PaymentServicesIDS = array_column($paymentTransactions->toArray(),'payment_services_id');
        $MerchantPaymentServices =  PaymentServices::viewData('ar')->whereIn('payment_services.id',$PaymentServicesIDS)->get();

        $transformer = new ServiceTransformer();

        return $this->json(true, __('Filter Data'),
            array_merge(
                ['services'=>$transformer->transformCollection($MerchantPaymentServices->toArray(),[$this->systemLang],'payment_services_api')],
                ['merchantStaff' =>$merchantStaff]
            )
        );
    }

    public function invoices(Request $request)
    {
        if (!staffCan('payment.invoice.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $inputs = $request->only(['merchant_id', 'invoice_id', 'payment_transaction_id', 'payment_services_id', 'status', 'merchant_staff_id', 'lang']);
        $validator = Validator::make($inputs, [
            'invoice_id' => 'nullable|string',
            'payment_transaction_id' => 'nullable|numeric',
            'payment_services_id' => 'nullable|numeric',
            'status' => 'nullable|in:pending,paid,reverse',
            'merchant_staff_id' => 'nullable|numeric',
            'merchant_id' => 'nullable|numeric|required',
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $lang = $this->systemLang;
        $eloquentData = Merchant::find($inputs['merchant_id'])->payment_invoice()
            ->with(['payment_transaction' => function ($query) use ($lang) {
                $query->with(['payment_services' => function ($query) use ($lang) {
                    $query->select([
                        'id',
                        'name_' . $lang . ' as name',
                        'icon',
                        'payment_service_provider_id'
                    ]);

                    $query->with(['payment_service_provider' => function ($query) use ($lang) {
                        $query->select([
                            'id',
                            'name_' . $lang . ' as name',
                            'logo'
                        ]);
                    }]);

                }]);
                $query->select([
                    'id',
                    'payment_services_id'
                ]);
            }])
            ->join('payment_transactions', 'payment_transactions.id', '=', 'payment_invoice.payment_transaction_id')
            ->select([
                'payment_invoice.id',
                'payment_invoice.payment_transaction_id',
                'payment_invoice.total',
                'payment_invoice.total_amount',
                'payment_invoice.status',
                'payment_invoice.created_at'
            ])
            ->orderBy('id', 'DESC');

        whereBetween($eloquentData, 'payment_invoice.created_at', $request->created_at1, $request->created_at2);

        if ($request->invoice_id) {
            $ids = explode(',', $request->invoice_id);
            $eloquentData->whereIn('payment_invoice.id', $ids);
        }

        if ($request->payment_transaction_id) {
            $eloquentData->where('payment_invoice.payment_transaction_id', '=', $request->payment_transaction_id);
        }

        if ($request->payment_services_id) {
            $eloquentData->where('payment_transactions.payment_services_id', '=', $request->payment_services_id);
        }

        if ($request->status) {
            $eloquentData->where('payment_invoice.status', '=', $request->status);
        }

        if ($request->merchant_staff_id) {
            $eloquentData->where('payment_transactions.model_id', '=', $request->merchant_staff_id);
        }




        $rows = $eloquentData->orderBy('created_at', 'DESC')->jsonPaginate();

        $transformer = new InvoiceTransformer();

        if (!$rows->items())
            return $this->json(false, __('No reports to display'),['balance' => Merchant::find($inputs['merchant_id'])->paymentWallet()->first()->balance . ' ' . __('LE')]);

        return $this->json(true, __('Merchant Report'),
            array_merge(
                $transformer->transformCollection($rows->toArray(), [$this->systemLang]),
                ['balance' => Merchant::find($inputs['merchant_id'])->paymentWallet()->first()->balance . ' ' . __('LE')]
            )
        );

    }

    private static function GetPaymentTransaction($transactionID)
    {

        if ($paymentTransaction = PaymentTransactions::find($transactionID))
            return array_map(function ($val, $key) {
                return ['name' => str_replace('parameter_', '', $key), 'value' => $val];
            }, $paymentTransaction->request_map, array_keys($paymentTransaction->request_map));
        else
            return null;
    }

    public function oneInvoice(Request $request)
    {
        if (!staffCan('payment.invoice.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $inputs = $request->only('merchant_id', 'invoice_id');

        $validator = Validator::make($inputs, [
            'invoice_id' => 'required|numeric|exists:payment_invoice,id',
            'merchant_id' => 'required|numeric',
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $paymentInvoice = Merchant::find($inputs['merchant_id'])->payment_invoice()->find($inputs['invoice_id']);
        $paymentTransaction = $paymentInvoice->payment_transaction()
            ->with(['payment_services' => function ($sql) {
                $sql->with('payment_service_provider');
            }])->first();

        $adapter = Payments::getTransactionData($paymentTransaction->id);

        $transformer = new OneInvoiceTransformer();
        $adapter['transactionID'] = $paymentTransaction->id;
        $adapter['provider_name_ar'] = $paymentTransaction->payment_services->payment_service_provider['name_ar'];
        $adapter['provider_name_en'] = $paymentTransaction->payment_services->payment_service_provider['name_en'];
        $adapter['service_name_ar'] = $paymentTransaction->payment_services['name_ar'];
        $adapter['service_name_en'] = $paymentTransaction->payment_services['name_en'];
        $adapter['service_description_ar'] = $paymentTransaction->payment_services['description_ar'];
        $adapter['service_description_en'] = $paymentTransaction->payment_services['description_en'];
        $adapter['invoice_status'] = $paymentInvoice->status;
        if (!$adapter['status'])
            return $this->json(false, __('Invoice not found'));
        return $this->json(true,__('Invoice Info'),
            array_merge(
                $transformer->ApiStaffTransform($adapter, $this->systemLang),
                ['balance' => Merchant::find($inputs['merchant_id'])->paymentWallet()->first()->balance . ' ' . __('LE')]
            )
        );

    }

    public function transfer(Request $request)
    {
        if (!staffCan('system.wallet.transfer-money-staff', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }


        $inputs = $request->only(
            [
                'amount',
                'wallet_id',
                'wallet_id_confirmation'
            ]
        );

        $validator = Validator::make($inputs, [
            'wallet_id' => 'required|numeric|exists:wallet,id|confirmed',
            'amount' => 'required|numeric'
        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $OwnerWallet = Auth::user()->paymentWallet;
        $wallet = Wallet::where('id', $request->wallet_id)->with('walletowner')->first();

        if (!isset($OwnerWallet)) {
            return $this->respondWithError(false, __('Your wallet not ready yet'));
        }

        if ($inputs['amount'] > $OwnerWallet->balance) {
            return $this->respondWithError(false, __('Not enough credit'));
        }

        if (($wallet->type != 'payment' || ($OwnerWallet->type != 'payment'))) {
            return $this->respondWithError(false, __('Can not transfer to this wallet'));
        }

        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $inputs['amount'],
            'wallet',
            $OwnerWallet->id,
            $wallet->id,
            null,
            null,
            'App\Models\MerchantStaff',
            Auth::id(),
            'paid'
        );

        $Transformer = new TransferTransformer();
        if (!$transfer['status']) {
            if ($transfer['code'] == 6)
                return $this->json(false, __('Can\'t transfer to yourself'),$Transformer->transform($transfer, $this->systemLang));
            else
                return $this->json(false, __('Could not transfer at this time'),$Transformer->transform($transfer, $this->systemLang));
        }

        if ($transfer['status']) {
            $transfer['to_wallet'] = $wallet;
        }

        return $this->json(true,__('Transfare'),
            array_merge($Transformer->transform($transfer, $this->systemLang), ['balance' => Auth::user()->paymentWallet()->first()->balance . ' ' . __('LE')])
        );

    }

    public function supervisorTeam(Request $request)
    {
        if (!staffCan('system.staff.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        if (Auth::user()->is_supervisor() == true  ) {
            $team = Auth::user();
            $managedStaff = $team->managed_staff()->orderBy('created_at','DESC');
            if(empty($managedStaff->first()))
                return $this->json(false, 'لا يوجد مندوبين');
            if($request->mobile){
                $managedStaff->where('mobile',$request->mobile);
            }
            if($request->name){
                $managedStaff->where('firstname', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('lastname', 'LIKE', '%' . $request->name . '%');
            }

            $managedStaff->with(['merchant'=>function($q){
                $q->where('status','active');
            }])->with('paymentWallet');


            $teamTransfared = new StaffTransformer();
            $team = $teamTransfared->transformCollection($managedStaff->jsonPaginate()->toArray(),[$this->systemLang],'subStaffWithMerchants');
            return $this->json(true,__('supervisor Team'), $team);

        } else {
            return $this->json(false, 'لست مشرف مندوبين');
        }

    }

    function salesMerchants(Request $request)
    {
        if (!staffCan('merchant.merchant.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $inputs = $request->only(['id']);
        $validator = Validator::make($inputs, [
            'id' => 'required|numeric|exists:staff,id',

        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $merchants = Merchant::viewData($this->systemLang)
            ->with(['paymentWallet' => function ($query)  {
                $query->select(['walletowner_id', 'walletowner_type', 'id as wallet_id', 'balance']);
            }])->where(['merchants.status' => 'active', 'merchants.staff_id' => $request->id]);
        if($request->mobile){
            $merchants->where('merchant_staff.mobile', '=',$request->mobile);
        }
        if ($request->merchant_id)
            $merchants->where('merchants.id', '=', $request->merchant_id);
        if ($request->name)
            $merchants->where('merchants.name_' . $this->systemLang, 'LIKE', '%' . $request->name . '%');
        if ($request->id)
            $merchants->where('merchants.staff_id', '=', $request->id);
        if ($request->category_id)
            $merchants->where('merchants.merchant_category_id', '=', $request->category_id);



        $walletTransformer = new  WalletTransformer;


        $seller_balance = Staff::find(Auth::id())->paymentWallet;
        $seller_balance = ['wallet_id'=>$seller_balance->id,'balance'=>$seller_balance->balance];

        if(empty($merchants->first())) {

            return $this->json(false, 'لا يوجد تجار لهذا المندوب');
        } else {
            $merchants = $merchants->orderBy('created_at','DESC')->jsonPaginate();
            return $this->json(true,__('sales Merchants'), [ 'seller' => $seller_balance, 'merchants' => $walletTransformer->transformCollection($merchants->toArray(),[$this->systemLang],'merchantWithWallet')]);
        }

    }

    public function about(){
        return $this->json(true,__('About'),['about'=>strip_tags(setting('aboutAppStaff'))]);
    }

    function walletTransactions(Request $request){

        if (!staffCan('system.wallet.transactions', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $inputs = $request->only(['id']);

        $validator = Validator::make($inputs, [
            'id' => 'required|numeric|exists:staff,id'

        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        if(Staff::find($request->id)->paymentWallet == null )
            return $this->json(false, __('No Wallet Transactions to display'),['balance' => Auth::user()->paymentWallet->balance . ' ' . __('LE')]);


        $eloquentData = Staff::find($request->id)->paymentWallet->allTransaction();

        whereBetween($eloquentData, 'created_at', $request->created_at1, $request->created_at2);
        if ($request->status) {
            $eloquentData->where('status', $request->status);
        }

        $eloquentData->where(function ($query) {
            $query->whereNull('model_id')->orWhere('model_type', '=', 'App\\Models\\WalletSettlement');
        });

        if ($request->status) {
            $eloquentData->where('status', '=', $request->status);
        }

        if ($request->type) {
            if ($request->type == 'settlement')
                $eloquentData->where('model_type', '=', 'App\\Models\\WalletSettlement');
            if ($request->type == 'transfer')
                $eloquentData->whereNull('model_id');
        }

        $rows = $eloquentData->orderBy('created_at', 'DESC')->jsonPaginate();
        if (!$rows->items())
            return $this->json(false, __('No Wallet Transactions to display'),['balance' => Auth::user()->paymentWallet->balance . ' ' . __('LE')]);

        $WalletTransactionsTransformer = new WalletTransactionsTransformer();
        return $this->json(true, __('Merchant Transactions details'),
            array_merge(
                $WalletTransactionsTransformer->transformCollection($rows->toArray(), [$this->systemLang]),
                ['balance' => Auth::user()->paymentWallet()->first()->balance . ' ' . __('LE')]
            )
        );

    }

    function visits(Request $request){

        if (!staffCan('system.visits.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }




        $eloquentData = Visits::select('id','shop_name','name','address','phone','mobile','status','created_at')
            ->where('staff_id',Auth::id());


        whereBetween($eloquentData, 'created_at', $request->created_at1, $request->created_at2);

        if($request->visit_id)
            $eloquentData->where('id', '=', $request->visit_id);

        if ($request->status) {
            $eloquentData->where('status', '=', $request->status);
        }

        if ($request->mobile) {
            $eloquentData->where('mobile', '=', $request->mobile);
        }

        if ($request->phone) {
            $eloquentData->where('phone', '=', $request->phone);
        }

        if ($request->name)
            $eloquentData->where('name' , 'LIKE', '%' . $request->name . '%');


        if ($request->shop_name)
            $eloquentData->where('shop_name' , 'LIKE', '%' . $request->shop_name . '%');

        if ($request->address)
            $eloquentData->where('address' , 'LIKE', '%' . $request->address . '%');

        if(empty($eloquentData->first()))
            return $this->json(false,__('No Results'));

        $visits = $eloquentData->orderBy('created_at','DESC')->jsonPaginate();


        $transformer = new VisitsTransformer();

        return $this->json(true,__('Visits'),$transformer->transformCollection($visits->toArray(),[$this->systemLang]));

    }

    function createVisit(Request $request){

        if (!staffCan('system.visits.create', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $inputs = $request->only(['shop_name','name','status','address','mobile','phone','statis','latitude','longitude','merchant_category_id','consumption','description']);
        $validator = Validator::make($inputs, [
            'shop_name' => 'required',
            'name'      => 'required',
            'address'   => 'required',
            'phone'     => 'numeric',
            'mobile'    => 'required|numeric',
            'status'    => 'required|in:ok,reject,hesitant,follow-up',
            'latitude'  => 'numeric',
            'longitude' => 'numeric',
           // 'merchant_category_id' => 'required|exists:merchant_categories,id',
            'consumption'=>'numeric',


        ]);

        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $inputs['staff_id'] = Auth::id();
        $inputs['merchant_category_id'] = 1;
        if($visite = Visits::create($inputs)){

            return $this->json(true,__('Data has been added successfully'));
        }else{
            return $this->json(false,__('Sorry Couldn\'t add Visit'));
        }

    }

    function deposits(Request $request){

        if (!staffCan('system.deposits.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $inputs = $request->only(['id','transaction_id','amount1','amount2','status','is_seen']);
        $validator = Validator::make($inputs, [
            'id' =>'nullable|numeric',
            'transaction_id' =>'numeric|nullable',
            'amount' =>'numeric|nullable',
            'status' =>'nullable|in:pending,approved,rejected',
            'is_seen' =>'nullable|in:yes,no'

        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $eloquentData = Deposits::select('*')->where(['creatable_id'=>Auth::id(),'creatable_type'=>'App\Models\Staff']);

        $eloquentData->with([
            'creatable',
            'bank',
            'readBy'
        ]);


        if($request->id){
            $eloquentData->where('id',$request->id);
        }

        if($request->transaction_id){
            $eloquentData->where('transaction_id',$request->transaction_id);
        }

        whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);
        whereBetween($eloquentData,'amount',$request->amount1,$request->amount2);

        if($request->status){
            $eloquentData->where('status',$request->status);
        }

        if($request->is_seen == 'yes'){
            $eloquentData->whereNotNull('read_by_staff_id');
        }elseif($request->is_seen == 'no'){
            $eloquentData->whereNull('read_by_staff_id');
        }

        if(empty($eloquentData->first())){
            return  $this->json(false,__('No Results')); //$this->respondNotFound([],__('No Results'));
        }

        $transformer = new DepositTransformer();


        return $this->json(true,__('Deposits'),$transformer->transformCollection($eloquentData->orderBy('created_at','DESC')->jsonPaginate()->toArray(),
            [$this->systemLang]));


    }

    function createDeposit(Request $request){

        if (!staffCan('system.deposits.create', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $inputs = $request->only([  'transaction_id','bank_id','amount','image']);
        $validator = Validator::make($inputs, [
            'transaction_id' => 'required|unique:deposits,transaction_id',
            'bank_id'        => 'required|exists:banks,id',
            'amount'         => 'required|numeric',
            'image'          => 'required|is_image',
            // 'date'           => 'required|before_or_equal:"'.date('Y-m-d').'"'

        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }

        $inputs['creatable_id']     = Auth::id();
        $inputs['creatable_type']   = 'App\Models\Staff';

        if($request->image){

            $file_name = 'imageDeposit_'.time().'.png'; //generating unique file name;
            file_put_contents('storage/deposits/'.date('y').'/'.date('m').'/'.$file_name,base64_decode($inputs['image']));
            $inputs['image'] =  $file_name;
        }
        $inputs['date'] = date('Y-m-d');

        if(Deposits::create($inputs)){
            return $this->json(true,__('Deposit has been added successfully'));
        }else {
            return $this->json(false,__('Sorry Couldn\'t add Deposit'));
        }




    }

    function totalConsumedSupervisor(Request $request){

        if (!staffCan('merchant.merchant.total-consumed', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $whereDate = null;

        if($request->created_at1 && $request->created_at2){
            $whereDate = "AND DATE(payment_invoice.created_at) BETWEEN '".$request->created_at1."' AND '".$request->created_at2."'";
        }

        $staff = \DB::select("
                SELECT staff.id,staff.firstname,staff.lastname, 
                  (
                  SELECT SUM(total_amount) FROM 
                    `payment_invoice` 
                  WHERE 
                    payment_invoice.status   = 'paid' AND 
                    payment_invoice.creatable_type = 'App\\\\Models\\\\Merchant' AND 
                    payment_invoice.creatable_id IN (
                      SELECT `id` FROM `merchants` WHERE `staff_id` IN (
                        SELECT `id` FROM `staff` as `managed_staff` WHERE managed_staff.supervisor_id = staff.id
                      )
                    )
                    $whereDate
                  ) as `total_consumed`
                FROM 
                  `staff`
                  INNER JOIN `permission_groups` ON `permission_groups`.`id` = `staff`.`permission_group_id`
                WHERE 
                  `permission_groups`.`id` IN(".setting('sales_supervisor_group_id').") AND
                  `staff`.`supervisor_id` IS NULL
                  
                  ORDER BY `total_consumed` DESC
            ");

        $transformer = new StaffTransformer();
//   //  dd($staff);
//      foreach ($staff as $key => $value){
//          $arr[] = $value->id;
//          $arr[] = $value->firstname .' '.$value->lastname;
//          $arr[] = $value->total_consumed;
//      }
////      dd($arr);
////    //  dd(collect($arr)->paginate(10));
////        //$staff = collect($arr)->jsonPaginate();
////        $staff = collect($staff)->jsonPaginate();


       // return response()->json(['status'=>true,'data'=>$arr]);
return $this->respondSuccess($staff,'totalConsumedSupervisor');
        return $this->json(true,__('total Consumed Staff'),$transformer->transformCollection($staff,[$this->systemLang],'staffTotalConsumed'));

    }

        function totalConsumedStaff(Request $request){

        if (!staffCan('api.staff.totalConsumedStaff', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

       // $inputs = $request->only(['id']);
//        $validator = Validator::make($inputs, ['id'=> 'required|exists:staff,id',]);
//        if ($validator->errors()->any()) {
//            return $this->ValidationError($validator, __('Validation Error'));
//        }

        $whereDate = null;

        if($request->created_at1 && $request->created_at2){
            $whereDate = "AND DATE(payment_invoice.created_at) BETWEEN '".$request->created_at1."' AND '".$request->created_at2."'";
        }

        $staffData = Staff::with('managed_staff')
            ->where('id',Auth::id())->first();

        if(empty($staffData['managed_staff']->toArray())){
            //return $this->respondNotFound([],__('No Staff Found'));
            $StaffIDS = Auth::id();
        } else {

            $StaffIDS = implode(',', array_column($staffData['managed_staff']->toArray(), 'id'));
        }


        $staff = \DB::select("
                SELECT staff.*, 
                  (
                  SELECT SUM(total_amount) FROM 
                    `payment_invoice` 
                  WHERE 
                    payment_invoice.status   = 'paid' AND 
                    payment_invoice.creatable_type = 'App\\\\Models\\\\Merchant' AND 
                    payment_invoice.creatable_id IN (
                      SELECT `id` FROM `merchants` WHERE `staff_id` = `staff`.`id`
                    )
                    $whereDate
                  ) as `total_consumed`
                FROM 
                  `staff`
                   WHERE `staff`.`id` IN ($StaffIDS)
                  ORDER BY `total_consumed` DESC
            ");

            $totalConsumedStaff = round( array_sum(array_column($staff,'total_consumed')));


            // Get current page form url e.x. &page=1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            // Create a new Laravel collection from the array data
            $itemCollection = collect($staff);

            // Define how many items we want to be visible in each page
            $perPage = 10;

            // Slice the collection to get the items to display in current page
            $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

            // Create our paginator and pass it to the view
            $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

            // set url path for generted links
            $exurl = '';
            if($request->created_at1 && $request->created_at2)
                $exurl = '?created_at1='.$request->created_at1.'&created_at2='.$request->created_at2;

            $paginatedItems->setPath($request->url().$exurl);




            $transformer = new StaffTransformer();
        return $this->json(true,__('total Consumed Staff'),['type'=>'staff','total'=>"$totalConsumedStaff",'items'=>$transformer->transformCollection($paginatedItems->toArray(),[$this->systemLang],'staffTotalConsumed')]);


    }

    function totalConsumedMerchant(Request $request){

        if (!staffCan('api.staff.totalConsumedMerchant', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $inputs = $request->only(['id']);
        $validator = Validator::make($inputs, ['id'=> 'required|exists:merchants,id',]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }


        $WHEREBetween = null;
        if($request->created_at1 && $request->created_at2){
            $WHEREBetween = "AND DATE(created_at) BETWEEN '".$request->created_at1."' AND '".$request->created_at2."'";
        }

        $staff_id = $inputs['id'];
        $merchants = \DB::select("
            SELECT id,staff_id,name_ar as `name`,
            (SELECT SUM(total_amount) FROM `payment_invoice` WHERE `status` = 'paid'
            AND `creatable_type` = 'App\\\\Models\\\\Merchant' AND `creatable_id` = merchants.id $WHEREBetween )
            as `total_paid` FROM `merchants` WHERE `staff_id` =  $staff_id
            ORDER BY `total_paid` DESC
        ");



       $totalConsumedMerchant = round( array_sum(array_column($merchants,'total_paid')));



       if($request->page)
           $page = $request->page;
       else
           $page = 1;
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page',$page);

        // Create a new Laravel collection from the array data
        $itemCollection = collect($merchants);

        // Define how many items we want to be visible in each page
        $perPage = 10;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        // set url path for generted links

        $exurl = '';
        if($request->created_at1 && $request->created_at2)
            $exurl = '&created_at1='.$request->created_at1.'&created_at2='.$request->created_at2;


            $exurl .= '';
        $paginatedItems->setPath($request->url().'?id='.$inputs['id'].$exurl);




        if(empty($paginatedItems))
                 return $this->json(false,__('No Merchants'));

        $transformer = new MerchantTransformer();
        return $this->json(true,__('total Consumed Of Merchant'),['type'=>'merchants','total'=>"$totalConsumedMerchant",'items'=>$transformer->transformCollection($paginatedItems->toArray(),[$this->systemLang],'merchantTotalConsumed')]);





    }

    public function banks(){

        $banks = Bank::select([
            'id',
            'banks.name_ar as name',
            'account_id'
        ])->get();
        return $this->json(true,__('Banks'),$banks);
    }

    public function createMerchantAction(Request $request){

        if (!staffCan('merchant.merchant.fast-create', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }

        $RequestData = $request->only([ 'name', 'description', 'contact', 'merchant_category_id', 'area_id', 'address',
            'branch_latitude', 'branch_longitude', 'staff_national_id', 'contractFile', 'contractTitle','contact'
        ]);
        $validator=  Validator::make($RequestData, [
            'name'               => 'required|min:3',
            'merchant_category_id'  => 'required|exists:merchant_categories,id',
            'area_id'               => 'required',
            'address'               => 'required|min:5',
            'branch_latitude'       => 'required|numeric',
            'branch_longitude'      => 'required|numeric',
            'contact.name.*'       => 'required',
            // 'contact.email.*'       => 'required|email',
            'contact.mobile.*'      => 'required|digits:11',
            'staff_national_id'     => 'required|digits:14|unique:merchant_staff,national_id',
            'contractTitle.*'       => 'required',
            'contractFile'        => 'required|is_image',
        ]);

        $customAttributes = [
            'merchant_category_id' => 'نوع التاجر',
            'area_id' => 'المنطقة',
            'contact' => 'التلفون',
            'branch_latitude' => 'الموقع',
            'branch_longitude' => 'الموقع',
            'staff_national_id' => 'الرقم القومى',
            'contractFile' => 'الملفات',
            'contractTitle' => 'نوع الملفات',
        ];
        $validator->setAttributeNames($customAttributes);
        if ($validator->errors()->any()) {

            return $this->ValidationError($validator, __('Validation Error'));
        }
        $I18N = new I18N_Arabic('Transliteration');

        // Start Request Data
        $theRequest            = $RequestData;
        $theRequest['area_id'] = getLastNotEmptyItem($RequestData['area_id']);

        // Merchant Staff Name
        $name = explode(' ', $theRequest['contact']['name'][0]);
        // dd($name);
        $theRequest['staff_firstname'] = $name[0];
        if (count($name) >= 2) {
            unset($name[0]);
            $theRequest['staff_lastname'] = implode(' ', $name);
        } else {
            $theRequest['staff_lastname'] = ' -- ';
        }

        $theRequest['staff_email'] = $theRequest['staff_national_id'] . '@merchant.egpay.com';

        try {
            $date = str_split(substr($theRequest['staff_national_id'], 1, 6), 2);
            if ($date[0] > date('y'))
                $date[0] = '19' . $date[0];
            else
                $date[0] = '20' . $date[0];
            $theRequest['staff_birthdate'] = Carbon::createFromFormat('Y-m-d', implode('-', $date));
        } catch (\Exception $e) {
            $theRequest['staff_birthdate'] = date('Y-m-d');
        }

        $staff_password = rand(111111, 999999);
        $images = [];
        if ($request->input('contractFile')) {
            $ContractNames =explode(',', $RequestData['contractTitle']);
            $files = explode(',',$RequestData['contractFile']);
            foreach ($ContractNames as $key => $val) {

                $file_name = 'image_'.uniqid().'.png';
                file_put_contents('storage/temp/contract/'.$file_name,base64_decode($files[$key]));
                $images[] = 'storage/temp/contract/'.$file_name;
                $uploads[$key]['path'] =  'temp/contract/'.$file_name;//$file->store('temp/contract');

                $uploads[$key]['title'] = $val;
            }
        };


        // ----------------------- NEW UPDATE
        Create::setUploadImage('beforeCreate');
        $ContractNames =explode(',', $RequestData['contractTitle']);
        $create = Create::Merchant(
        // Merchant
            [
                'is_reseller' => 'in-active',
                'area_id' => $RequestData['area_id'],
                'name_ar' => $theRequest['name'],
                'name_en' => $I18N->ar2en($theRequest['name']),
                'description_ar' => $theRequest['description'],
                'description_en' => $I18N->ar2en($theRequest['description']),
                'address' => $theRequest['address'],
                'logo' => null,
                'merchant_category_id' => $theRequest['merchant_category_id'],
                'status' => 'active',
                'staff_id' => Auth::id(),
                'parent_id' =>1
            ],

            // Contact
            $theRequest['contact'],
            // Branch
            [
                'name_ar' => $theRequest['name'] . ' - الفرع الرئيسي',
                'name_en' => $I18N->ar2en($theRequest['name']) . ' - Main Branch',
                'address_ar' => $theRequest['address'],
                'address_en' => $I18N->ar2en($theRequest['address']),
                'description_ar' => $theRequest['description'],
                'description_en' => $I18N->ar2en($theRequest['description']),
                'latitude' => $theRequest['branch_latitude'],
                'longitude' => $theRequest['branch_longitude']
            ],

            // Staff
            [
                'firstname' => $theRequest['staff_firstname'],
                'lastname' => $theRequest['staff_lastname'],
                'username' => null,
                'national_id' => $theRequest['staff_national_id'],
                'address' => $theRequest['address'],
                'birthdate' => $theRequest['staff_birthdate'],
                'email' => $theRequest['staff_email'],
                'password' => $staff_password,
                'mobile' => $theRequest['contact']['mobile'][0]
            ],

            // Contract
            [
                'plan_id' => setting('main_merchant_plan_id'),
                'description' => $theRequest['description'],
                'price' => 0,
                'admin_name' => $theRequest['staff_firstname'] . ' ' . $theRequest['staff_lastname'],
                'admin_job_title' => 'Manager'
            ],

            // Contract Files

            @$images,


            // Contract File's title
            @$ContractNames,

            null,
            null,
            null
        );

        if ($create) {
            Create::setUploadImage('afterCreate');
            $uploads = new \Illuminate\Support\Collection();
            if (count($images)) {
                foreach ($images as $oneFile) {
                    $File = new File($oneFile);
                    $Moved = $File->move('storage/contract/' . $create->id . '/');
                    $uploads->push(new Upload([
                        'path' => (($Moved) ? $Moved->getPath() . '/' . $Moved->getBasename() : $oneFile['path']),
                        'title' => 'title',
                        'model_id' => $create->id,
                        'model_type' => get_class($create)
                    ]));

                    $new_path = str_replace('storage/temp/contract/', 'storage/contract/' . $create->id . '/', $oneFile);
                    $contract_id = MerchantContract::where('merchant_id', $create->id)->value('id');
                    // $images_ids = Upload::where('model_id', $contract_id)->get(['id','path']);

                    $uploaded_imgs = Upload::where('model_id',$contract_id)->get();
                    foreach ($uploaded_imgs as $key=> $img ){
                        $new_path = str_replace('storage/temp/contract/', 'storage/contract/' . $create->id . '/', $img->path);
                        Upload::where('id',$img->id)->update(['path'=>$new_path]);
                    }


                }
            }

            $merchant_data = [
                'name'=>$create->name_ar,
                'merchant_id'=>$create->id,
                'staff_id'=>$create->merchant_staff_id,
                'staff_password'=>$staff_password,
                'wallet_id'=>$create->paymentWallet->id,

            ];
            return $this->json(true,__('Merchant Created Succesfull'),$merchant_data );
        }else {
            return $this->json(false, __('Sorry Couldn\'t add Merchant'));

        }
    }

    public function version(){

        return $this->json(true,'',['version'=> setting('staff_mobile_app_version')]);
    }



    public function checkuserStatus($user = null)
    {
        $userobj = (($user) ? $user : (Auth::user()) ? Auth::user() : null);
        if (isset($userobj) && ($userobj->status == 'in-active'))
            return $this->respondWithError(false, __('Deactivated Account'));
        //TODO add Check for Merchant if its active
        if (isset($userobj) && ($userobj->merchant()->status == 'in-active'))
            return $this->respondWithError(false, __('Deactivated Merchant'));
    }

    function no_access()
    {
        return ['status' => false, 'msg' => __('You don\'t have permission to preform this action')];
    }


    function headerdata($keys)
    {
        return request()->only($keys);
        /*
        if(count(return $this->jsonData) == 0)
            return [];
        if(is_array($keys)) {
            $response = [];
            foreach ($keys as $key) {
                $response[$key] = array_key_exists($key,return $this->jsonData) ? return $this->jsonData[$key] : null;
            }
            request()->merge($response);
            return $response;
        } elseif (isset($keys)){
            $response = array_key_exists($keys,return $this->jsonData)  ? [$keys=>return $this->jsonData[$keys]] : null;
                request()->merge($response);
            return $response;
        } else {
            request()->merge(return $this->jsonData);
            return $this->jsonData;
        }
        */
    }


    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    public function setStatusCode($StatusCode)
    {
        $this->StatusCode = $StatusCode;
        return $this;
    }


    public function getStatusCode()
    {
        return $this->StatusCode;
    }

    public function setCode($code)
    {
        $this->Code = $code;
        return $this;
    }


    function ReturnMethod($condition, $truemsg, $falsemsg, $data = false)
    {
        if ($condition)
            return ['status' => true, 'msg' => $truemsg, 'data' => $data];
        else
            return ['status' => false, 'msg' => $falsemsg, 'data' => $data];
    }

    public function respondSuccess($data,$type = null , $message = 'Success')
    {
        return $this->setStatusCode(200)->setCode(200)->respondWithoutError($data,$type ,$message);
    }

    public function respondCreated($data, $message = 'Row has been created')
    {
        return $this->setStatusCode(200)->setCode(200)->respondWithoutError($data, $message);
    }

    public function respondNotFound($data, $message = 'Not Found!')
    {
        return $this->setStatusCode(200)->setCode(200)->respondWithError($data, $message);
    }

    public function respond($data, $headers = [])
    {
        $data['version'] = setting('staff_mobile_app_version');
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function permissions($permission = false)
    {
        $permissions = \Illuminate\Support\Facades\File::getRequire('../app/Modules/Merchant/Permissions.php');
        return $permission ? isset($permissions[$permission]) ? $permissions[$permission] : false : $permissions;
    }

    public function permissionsNames($permission = false, $reverse = false)
    {
        $permissions = $this->permissions();
        $data = [];
        foreach ($permissions as $key => $val) {
            $data = array_merge($data, [$key => __(ucfirst(str_replace('-', ' ', $key)))]);
        }
        if ($reverse)
            return array_search($permission, $data);
        else
            return $data ? isset($data[$permission]) ? $data[$permission] : false : $data;
    }

    public function ValidationError($validation, $message)
    {

        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function ($key, $val) {
            return ['key' => $key, 'val' => implode('|', $val)];
        }, array_keys($errorArray), $errorArray), 'val', 'key');

        $data['version'] = $this->lastupdate;
        //$data['msgs'] = implode("\n",array_flatten($errorArray));

        return $this->setCode(200)->respondWithError($data, implode("\n", array_flatten($errorArray)));
    }

    public function DownloadStaffApk(Request $request)
    {
        header('Content-Type: application/vnd.android.package-archive');
        return response()
            ->download(storage_path('app/public/latest_staff_app.apk'));
    }

    public function merchantName(Request $request)
    {
        $RequestData = $request->only('wallet_id');
       // dd($RequestData);
        $validator = Validator::make($RequestData, [
            'wallet_id' => 'required|exists:wallet,id',
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $lang = $this->systemLang;
        $wallet = Wallet::where('id',$request->wallet_id)->first();

       if ($wallet->walletowner_type == 'App\Models\Staff'){
           $name = $wallet->walletowner->Fullname;
       }
        if ($wallet->walletowner_type == 'App\Models\Merchant'){
            $name = $wallet->walletowner->{'name_'.$lang};
        }

        if (!$name){
            return $this->json(false,__('There No data available'));
           // return $this->json(false,__('No Merchants'));
        }else{
            return $this->json(true,__('Wallet owner name'),['staff_name'=>$name]);
        }
    }

    function oneVisits(Request $request){

        if (!staffCan('system.visits.index', Auth::id())) {
            return $this->json(false,__('Youd Don\'t have permission to this request'),[],403);
        }
        $RequestData = $request->only('visit_id');
        $validator = Validator::make($RequestData, [
            'visit_id' => 'required|exists:visits,id',
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $eloquentData = Visits::select('id','shop_name','name','address','phone','mobile','status','created_at')
            ->where('id','=',$request->visit_id)->first();
        if(empty($eloquentData))
            return $this->json(false,__('No Results'));

        $transformer = new VisitsTransformer();
        
       return $this->json(true,__('One Visit'),$transformer->transform($eloquentData,$this->systemLang));
    }
    public function requestBalance(Request $request)
    {
        $theRequest = $request->only([
            'amount',
            'description'
        ]);
        $validator = Validator::make($theRequest, [
            'amount' => 'required|numeric',
            'description' => 'required'
        ]);
        if ($validator->errors()->any()) {
            return $this->ValidationError($validator, __('Validation Error'));
        }
        $theRequest['staff_id']     =  Auth::id();
        $theRequest['staff_balance_at_request']   =  Auth::user()->paymentWallet->balance;

        if(RequestBalance::create($theRequest)) {

//            if (!empty(setting('accountants_ids_notifications'))) {
//                $monitorStaff = Staff::whereIn('id', explode("\n", setting('accountants_ids_notifications')))
//                    ->get();
//                foreach ($monitorStaff as $key => $value) {
//                    $value->notify(
//                        (new UserNotification([
//                            'title' => 'Request Balance From: ' . Auth::user()->Fullname,
//                            'description' => Auth::user()->Fullname . ' Request ' . amount($theRequest['amount'], true),
//                            'url' => route('system.request-balance.index')
//                        ]))
//                            ->delay(5)
//                    );
//                }
//            }

            return $this->json(true,__('Request Balance has been successfully Requested'));
        }else{
            return $this->json(false,__('Sorry Couldn\'t Request Balance'));
        }

    }

}