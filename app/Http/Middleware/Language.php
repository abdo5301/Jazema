<?php

namespace App\Http\Middleware;

use App\Modules\Api\User\MerchantsApiController;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Language
{
    public function handle($request, Closure $next)
    {

        // DEBUG BAR

        if(!Auth::check() || !(Auth::user() instanceof \App\Models\Staff) || Auth::user()->id !== 1) {
            \Debugbar::disable();
        }





        if(Auth::check()) {

            if(in_array($request->lang,['ar','en'])){

                if($request->lang != Auth::user()->language_key){
                    Auth::user()->update(['language_key'=>$request->lang]);
                }

                App::setLocale($request->lang);

            }elseif(in_array($request->data_lang,['ar','en'])){

                if($request->data_lang != Auth::user()->language_data_key){
                    Auth::user()->update(['language_data_key'=>$request->data_lang]);
                }

                \DataLanguage::set($request->data_lang);

            } else {
                if(!empty(Auth::user()->language_key)) {
                    App::setLocale(Auth::user()->language_key);
                    \DataLanguage::set(Auth::user()->language_data_key);
                }else {
                    App::setLocale('ar');
                    \DataLanguage::set('ar');
                }
            }

        } else {
            if(!empty($request->lang)) {
                App::setLocale($request->lang);
                \DataLanguage::set($request->lang);
            }else {
                App::setLocale('ar');
                \DataLanguage::set('ar');

            }
        }



        return $next($request);
    }
}