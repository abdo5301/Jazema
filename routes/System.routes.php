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
// Route::get('/','HomeSite@index')->name('system.home-site');
Route::get('/ajax','AjaxController@get')->name('system.ajax.get');
Route::post('/ajax','AjaxController@post')->name('system.ajax.post');
// System
Route::group(['prefix'=>'system'],function(){

    // Authentication Routes...
//    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//    Route::post('login', 'Auth\LoginController@login');
//    Route::post('logout', 'Auth\LoginController@logout')->name('system.logout');


    Auth::routes();

    Route::get('/','Dashboard@index')->name('system.dashboard');
    Route::get('/development','Dashboard@development')->name('system.development');
    Route::post('/encrypt','Dashboard@encrypt')->name('system.encrypt');


    Route::get('/logout','Dashboard@logout')->name('system.logout');
    Route::any('/change-password','Dashboard@changePassword')->name('system.change-password');

    // Activity LOG
    Route::get('/activity-log/{ID}', 'ActivityController@show')->name('system.activity-log.show'); //
    Route::get('/activity-log', 'ActivityController@index')->name('system.activity-log.index'); //

    // Notifications
    Route::get('/notifications/{ID}', 'NotificationController@url')->name('system.notifications.url'); //
    Route::get('/notifications', 'NotificationController@index')->name('system.notifications.index'); //

    // Setting
    Route::get('/setting', 'SettingController@index')->name('system.setting.index'); //
    Route::patch('/setting', 'SettingController@update')->name('system.setting.update'); //


    // Permission Group
    Route::resource('/permission-group','PermissionGroupController',['as'=>'system']); //
    // Users
    Route::resource('/users/job', 'UserJobController',['as'=>'system']); //
    Route::resource('/users/relative/relation', 'UserRelativeRelationController',['as'=>'system']); //
    Route::resource('/users/relatives', 'UserRelativesController',['as'=>'system']); //
    Route::resource('/users/address', 'UsersAddressesController',['as'=>'system']); //
    Route::resource('/users', 'UserController',['as'=>'system']); //
    Route::any('/users/address/isDefault/{address}','UsersAddressesController@isDefault')->name('system.staff.default-address');
    Route::resource('/order/history/status','OrderHistoryStatusController',['as'=>'system','except'=>['show','destroy']]);
    Route::resource('/product/template/attributes','MerchantProductTemplateAttributeController',['as'=>'system']);

    // Staff
    Route::delete('/staff/deleteManagedStaff/{id}','StaffController@deleteManagedStaff')->name('system.staff.delete-managed-staff');
    Route::post('/staff/addManagedStaff','StaffController@addManagedStaff')->name('system.staff.add-managed-staff');
    Route::post('/staff/changeSalesSupervisor','StaffController@changeSalesSupervisor')->name('system.staff.change-sales-supervisor');
    Route::post('/staff/changeStatus','StaffController@changeStatus')->name('system.staff.change-status');
    Route::get('/staff/log','StaffController@staffLog')->name('merchant.staff-log');
    Route::get('/staff/staff-transfare-log','StaffController@staffSalesLog')->name('staff.staff-sales-log');
    Route::post('/staff/change-merchant-sales','StaffController@changeMerchantSales')->name('system.staff.change-merchant-sales');

    Route::resource('/staff', 'StaffController',['as'=>'system']); //
    Route::resource('/item_category', 'ItemCategoryController',['as'=>'system']); //
    Route::resource('/item_type', 'ItemTypeController',['as'=>'system']); //
    Route::resource('item/attributes','AttributesController',['as'=>'system']);
    Route::resource('template/option','TemplateOptionController',['as'=>'system']);
    Route::post('/user/get-attributes','UserController@getAttributes')->name('user.attribute.get-attribute');

    Route::get('/item/template/options', 'ItemController@merchantOptions')->name('product-template-option');

    Route::get('/item/template/merchant-attributes','MerchantProductTemplateAttributeController@merchantProductTemplateAttributeFunction')->name('product-template-attribute');

    Route::post('/item-remove-image', 'ItemController@remove_image')->name('product-remove-image');//
    Route::post('/item-temp-upload', 'ItemController@upload_image')->name('upload-temp-image');//

    //Route::post('/item-temp-upload', 'ItemController@upload_image')->name('upload-temp-image');//

    Route::post('/item/get-attributes','ItemController@getAttributes')->name('attribute.get-attribute');

  Route::post('/item/type/check', 'ItemController@getItemTypes')->name('system.check-item-type');

    Route::resource('/item', 'ItemController',['as'=>'system']); //

    // Area
    Route::resource('/area-type', 'AreatypesController',['as'=>'system']); //
    Route::resource('/area', 'AreaController',['as'=>'system']); //

    // Send Email & SMS
    Route::resource('/sender', 'SenderController',['as'=>'system','except'=>['edit','update','destroy','create']]);


    // Ajax
    
       Route::post('/item/option/check', 'DealController@checkProductOption')->name('system.check-product-option');
       
        Route::post('/item/option', 'DealController@productOption')->name('system.product-option');


    Route::resource('/deal', 'DealController',['as'=>'system']); //
    
    
Route::resource('pages','PagesController',['as'=>'system']);
Route::resource('services','ServicesController',['as'=>'system']);

    // Chat
    Route::get('/chat','ChatController@index')->name('system.chat.index');
    Route::get('/chat/get-conversation/{ID}','ChatController@getConversation')->name('system.chat.get-conversation');


});

