<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\App;
use Closure;
use Auth;

class ApiStaff
{
    public function handle($request, Closure $next)
    {
        // Disabled Account
        if(Auth('ApiStaff')->check() && Auth::user()->status == 'in-active'){
            return response()->json([
                'status' => false,
                'msg' => __('Unauthenticated'),
                'code' => 302,
                'data' => (object)[]
            ], 200);
        }

// exception to islam
//        if(!empty($request->all())){
//            foreach ($request->all() as $key=>$value){
//                if($request[$key] == "null" || $request[$key] == null) {
//                    unset($request['category_id']);
//                }
//            }
//
//        }

     //   App::setLocale('ar');

        return $next($request);
    }
}