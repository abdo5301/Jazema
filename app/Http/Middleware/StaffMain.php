<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class StaffMain
{
    public function handle($request, Closure $next)
    {
         if($_SERVER['SERVER_PORT'] == 7443) {
             return ['status' => false, 'msg' => 'You Cannot Access'];
         }
        return $next($request);
    }
}


