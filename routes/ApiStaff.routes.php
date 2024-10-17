<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// staff
Route::group(['prefix'=>'staff','namespace'=>'Staff'],function(){
   // Auth::routes();


//  Route::get('/dd','ApiStaffController@dd')->name('api.staff.dd');
  Route::post('/login','ApiStaffController@login')->name('api.staff.login');
  Route::post('/changePassword','ApiStaffController@changePassword')->name('api.staff.changePassword');
  Route::post('/logout','ApiStaffController@logout')->name('api.staff.logout');

  Route::post('/about','ApiStaffController@about')->name('api.staff.about');


  Route::post('/merchants','ApiStaffController@merchants')->name('api.staff.merchants');
  Route::post('/merchantInfo','ApiStaffController@merchantInfo')->name('api.staff.merchantInfo');
  Route::post('/walletTransactions','ApiStaffController@walletTransactions')->name('api.staff.walletTransactions');
  Route::post('/myWalletTransactions','ApiStaffController@myWalletTransactions')->name('api.staff.myWalletTransactions');
  Route::post('/transactions','ApiStaffController@transactions')->name('api.staff.transactions');
  Route::post('/oneTransactions','ApiStaffController@OneWalletTransactions')->name('api.staff.oneTransactions');
  Route::post('/invoices','ApiStaffController@invoices')->name('api.staff.invoices');
  Route::post('/invoicesFilterData','ApiStaffController@invoicesFilterData')->name('api.staff.invoicesFilterData');
  Route::post('/oneInvoice','ApiStaffController@oneInvoice')->name('api.staff.oneInvoice');
  Route::post('/transfer','ApiStaffController@transfer')->name('api.staff.transfer');
  Route::post('/area/{id}/{type_id}','ApiStaffController@getAreaChildern')->name('api.staff.area');
  Route::post('/createMerchant','ApiStaffController@createMerchant')->name('api.staff.createMerchant');
  Route::post('/createMerchantAction','ApiStaffController@createMerchantAction')->name('api.staff.createMerchantAction');
  Route::post('/FastCreateAction','ApiStaffController@FastCreateAction')->name('api.staff.FastCreateAction');
  Route::post('/supervisorTeam','ApiStaffController@supervisorTeam')->name('api.staff.supervisorTeam');
  Route::post('/salesMerchants','ApiStaffController@salesMerchants')->name('api.staff.salesMerchants');
  Route::post('/visits','ApiStaffController@visits')->name('api.staff.visits');
  Route::post('/createVisit','ApiStaffController@createVisit')->name('api.staff.createVisit');
  Route::post('/deposits','ApiStaffController@deposits')->name('api.staff.deposits');
  Route::post('/createDeposit','ApiStaffController@createDeposit')->name('api.staff.createDeposit');
  Route::post('/totalConsumedSupervisor','ApiStaffController@totalConsumedSupervisor')->name('api.staff.totalConsumedSupervisor');
  Route::post('/totalConsumedStaff','ApiStaffController@totalConsumedStaff')->name('api.staff.totalConsumedStaff');
  Route::post('/totalConsumedMerchant','ApiStaffController@totalConsumedMerchant')->name('api.staff.totalConsumedMerchant');
  Route::post('/banks','ApiStaffController@banks')->name('api.staff.banks');
  Route::post('/version','ApiStaffController@version')->name('api.staff.version');

    Route::get('/latest-app.apk','ApiStaffController@DownloadStaffApk')->name('api.staff.app');



    Route::post('/wallet-owner-name','ApiStaffController@merchantName')->name('api.staff.wallet-owner-name');
    Route::post('/one-visit','ApiStaffController@oneVisits')->name('api.staff.one-visits');

    Route::post('/request-balance','ApiStaffController@requestBalance')->name('api.staff.request-balance');


});