<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\DBSessionAuth;
use App\Models\UserJob;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         if (empty(\DataLanguage::get())){
            \DataLanguage::set('en');
        }

        $lang = \DataLanguage::get();
        view()->composer('web.layouts',function ($data)use($lang){
            $data->with('userJobs',array_column(UserJob::get(['id','name_'.$lang .' as name'])->toArray(),'name','id'));
        });
        // @TODO: TO BE DELETED
        // \URL::forceScheme('https');
        
        set_time_limit(0);
        ignore_user_abort(true);
        /*
        * Fix migrate Error
        */
        Schema::defaultStringLength(191);

       // \App\Models\Merchant::observe(\App\Observers\MerchantObserver::class);
       // \App\Models\MerchantContract::observe(\App\Observers\MerchantContractObserver::class);
       // \App\Models\MerchantProduct::observe(\App\Observers\MerchantProductObserver::class);

        Auth::extend('DBSessionAuth', function($app,$name, array $config) {
            $providerData = config('auth.providers.'.$config['provider']);
            return new DBSessionAuth($providerData['model'],$name);
        });
// Enable pagination
        if (!Collection::hasMacro('paginate')) {

            Collection::macro('paginate',
                function ($perPage = 2, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage)->values()->all(), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
