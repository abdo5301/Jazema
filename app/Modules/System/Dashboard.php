<?php

namespace App\Modules\System;


use App\Models\Deal;
use App\Models\ErrorLog;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\User;
use App\Models\Stage;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Facades\Crypt;
use App\Libs\SMS;


use App\Libs\Payments\Payments;


class dashboard extends SystemController{


    public function __construct(Request $request){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }
 

    public function index(Request $request){
//        $arrX = array("pending", "inprogress","done", "stopping","pause");
//
//for($i=0 ; $i<= 200 ; $i++) {
//    Deal::create([
//        'item_id' => rand(28, 500),
//        'item_owner_id' => rand(5, 10),
//        'user_id' => rand(5, 10),
//        'total_price' => rand(1, 9999999),
//        'notes' => "simply dummy text of the printing and typesetting",
//        'status' => $arrX[rand(0, 4)],
//    ]);
//
//}
        $dateToday = date('Y-m-d');
        $countUsers           = new User();

        $countTodayDeals       = Deal::whereRaw('DATE(`deals`.`created_at`) = ?',[$dateToday]);
        $countTodayItems       = Item::whereRaw('DATE(`items`.`created_at`) = ?',[$dateToday]);
        $countAllDeals         = Deal::count();

        $this->viewData['countUsers']           = ($countUsers === 0) ? 0 : $countUsers->count();

        $this->viewData['countTodayDeals']        = $countTodayDeals->count();
        $this->viewData['countTodayItems']  = $countTodayItems->count();
        $this->viewData['countAllDeals']    = $countAllDeals;

        // --- Line Two

//        $this->viewData['PaymentTransaction'] = $PaymentTransaction->count();
//        $this->viewData['PaymentInvoice']     = $PaymentInvoice->count();
//        $this->viewData['PaymentServices']    = $PaymentServices->count();





        // --- Payment Overview Dashboard

        $itemCount = Item::select([
            DB::raw('MONTH(created_at) as `month`'),
            DB::raw('COUNT(*) as `count`'),
        ])
            ->whereRaw("YEAR(`created_at`) = '".date('Y')."'")
            ->where('status','active')
            ->groupBy(DB::raw("MONTH(`created_at`)"))
            ->get()
            ->toArray();
//        dd($itemCount);

        $this->viewData['itemCount'] = array_column($itemCount,'count','month');


        $dealsCount = Deal::select([
            DB::raw('MONTH(created_at) as `month`'),
            DB::raw('COUNT(*) as `count`'),
        ])
            ->whereRaw("YEAR(`created_at`) = '".date('Y')."'")
            ->groupBy(DB::raw("MONTH(`created_at`)"))
            ->get()
            ->toArray();
//        dd($paymentTransactionCount);

        $this->viewData['dealsCount'] = array_column($dealsCount,'count','month');

        return $this->view('dashboard.index',$this->viewData);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('system.dashboard');
    }

    public function changePassword(Request $request){
        if($request->method() == 'POST'){

            $this->validate($request,[
                'old_password'          => 'required',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required'
            ]);

            if (!Hash::check($request->old_password, Auth::user()->getAuthPassword())){
                return back()
                    ->with('status','danger')
                    ->with('msg',__('Old Password is incorrect'));
            }

            Staff::find(Auth::id())->update(['password'=>bcrypt($request->password)]);

            return back()
                ->with('status','success')
                ->with('msg',__('Your Password Has been changed successfully'));
        }else{
            $this->viewData['pageTitle'] = __('Change Password');
            return $this->view('dashboard.change-password',$this->viewData);
        }
    }

    public function encrypt(Request $request){
        $type = $request->encrypt_type;
        $text = $request->encrypt_text;

        if(
            !in_array($type,['encrypt','decrypt']) ||
            empty($text)
        ){
            return ['status'=>false,'msg'=>__('Please Enter valid data')];
        }

        if($type == 'encrypt'){
            return ['status'=>true,'data'=> Crypt::encryptString($text)];
        }else{
            return ['status'=>true,'data'=> Crypt::decryptString($text)];
        }

    }

    public function development(Request $request){

//        if(!in_array(Auth::id(),[1,42]) ){
//            abort(403);
//        }

        switch ($request->type){

            case 'stage':

                $users = User::get();

                foreach($users as $user){
                    $stage_id =  Stage::where('user_id',$user->id)->first()->id;
                    $user->items()->update(['stage_id'=>$stage_id]);
            }

                break;

            case 'artisan' :
                exec('composer dump-autoload');
                exec('db:seed --class=UsersTableSeeder');
//                \Artisan::call('dump-autoload');
                \Artisan::call('db:seed');
//                \Artisan::call('db:seed --class=UsersTableSeeder');
echo 'ddd';
                break;

            case 'phpinfo':
                phpinfo();
                break;


            case 'ip':
                echo getRealIP();
                break;

            case 'clear-cache':

                \Artisan::call('storage:link');
                \Artisan::call('config:clear');
                \Artisan::call('config:cache');
                \Artisan::call('cache:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                echo 'Cache Cleared';
                break;

            case 'clear-opcache':
                opcache_reset();
                echo 'Cache Cleared';
                break;


        }

    }

}