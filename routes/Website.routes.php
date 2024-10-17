<?php

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/ajax/get','AjaxController@get')->name('web.ajax.get');
Route::get('/','HomeController@index')->name('web.index');
Route::get('/about-us','HomeController@aboutUs')->name('web.about-us');
Route::get('/page/{slug}','HomeController@page')->name('web.page');
Route::post('/contact-us-post','HomeController@contactUs')->name('web.contact-us');
Route::post('/request-register-merchant','HomeController@RequestRegisterMerchant')->name('web.request-register-merchant');
Route::get('/{lang?}','HomeController@index')->name('web.index');

Route::group(['prefix'=>'item'],function() {
    Route::post('/get-items', 'ItemController@getItems')->name('web.item.get-items');
    Route::get('/details/{slug}', 'ItemController@Details')->name('web.item.details');
    Route::get('/category/{slug}', 'ItemController@category')->name('web.item.category');
    Route::get('/type/{slug}', 'ItemController@type')->name('web.item.type');
    Route::post('/mail/send', 'ItemController@sendMail')->name('web.item.send-mail');
    Route::post('/mail/send', 'ItemController@sendMail')->name('web.item.send-mail');
    //Route::post('/add-message', 'ItemController@add_message')->name('web.item.rank-item');
    Route::get('/fastSearchItem', 'ItemController@fastSearchItem')->name('web.item.fastSearchItem');
    Route::post('/item/get-attributes','ItemController@getItemAttributes')->name('web.search.get-attribute');
});

Route::group(['prefix'=>'user'],function() {
    Route::get('/profile/{slug}', 'UserController@profile')->name('web.user.profile');
    Route::get('/wishlist', 'UserController@wishList')->name('web.user.wishlist');
    Route::post('/addWish', 'UserController@addWish')->name('web.user.add-wish');
    Route::any('/delete-wish/{id}', 'UserController@deleteWish')->name('web.user.delete-wish');
    Route::get('/searchUser', 'UserController@searchUser')->name('web.user.searchUser');
    Route::any('/delete-items/{id}', 'UserController@deleteItems')->name('web.user.delete-items');
    Route::get('/edit-item/{id}', 'UserController@editItem')->name('web.user.edit-item');
    Route::post('/edit-item-action', 'UserController@editItemAction')->name('web.user.item-edit-action');
    Route::post('/store-items', 'UserController@storeItems')->name('web.user.store-items');
    Route::post('/make-deal', 'UserController@createDeal')->name('web.user.make-deal');
    Route::post('/get-deal-options', 'UserController@get_deal_options')->name('web.user.get-deal-options');
    Route::post('/item/get-options-item-deal','UserController@get_item_deal_options')->name('web.user.get-options-item-deal');//abdo
    //Route::get('/edit-items/{id}', 'UserController@editItems')->name('web.user.edit-items');



    Route::get('/add-items', 'UserController@addItems')->name('web.user.add-items');
    Route::get('/my-items', 'UserController@myItems')->name('web.user.myitems');
    Route::get('/edit-profile', 'UserController@editProfile')->name('web.user.edit-profile');
    Route::post('/edit-profile/{user}', 'UserController@updateProfile')->name('web.user.update-profile');
    Route::get('/logout', 'UserController@logout')->name('web.user.logout');
    Route::post('/login', 'UserController@login')->name('web.user.login');
    Route::post('/register', 'UserController@register')->name('web.user.register');
    Route::post('/get-attributes','UserController@getAttributes')->name('web.user.get-attribute');
    Route::post('/get-attributes-user','UserController@get_old_user_attributes')->name('web.user.get-attribute-user');//abdo
    Route::post('/item/type/check', 'UserController@getItemTypes')->name('web.user.check-item-type');
    Route::post('/item-remove-image', 'UserController@remove_image')->name('web.item-remove-image');//
    Route::post('/item-temp-upload', 'UserController@upload_image')->name('web.upload-temp-image');//
    Route::get('/item/template/options', 'UserController@merchantOptions')->name('web.item-template-option');
    Route::post('/item/get-attributes','UserController@getItemAttributes')->name('web.attribute.get-attribute');
    Route::post('/item/get-attributes-item','UserController@get_old_item_attributes')->name('web.attribute.get-attribute-item');//abdo
    Route::post('/item/get-options-item','UserController@get_old_item_options')->name('web.user.get-option-item');//abdo
    Route::get('/mail','UserController@userMails')->name('web.user-mail');
    Route::post('/mail/send', 'UserController@sendMail')->name('web.user.send-mail');
    Route::post('/mail/view-mail/{id}','UserController@viewMail')->name('web.user.view-mail');
    Route::delete('/delete-mail/{email}','UserController@deleteEmail')->name('web.user.delete-mail');
    Route::get('/add-stage','UserController@addStage')->name('web.user.add-stage');
    Route::post('/store-stage','UserController@storeStage')->name('web.user.store-stage');
    Route::get('/stages','UserController@userStage')->name('web.user.stages');
    Route::get('/stage-edit/{id}','UserController@editStage')->name('web.user.stage-edit');
    Route::post('/stage-update/{stage}','UserController@updateStage')->name('web.user.stage-update');
    Route::delete('/stage-delete/{stage}','UserController@deleteStage')->name('web.user.stage-delete');
    Route::any('deals','UserController@deals')->name('web.user.deals');
    Route::post('deal/update-status/{id}','UserController@updateStatus')->name('web.user.deal.update-status');
    Route::post('/rank-user', 'UserController@rankUser')->name('web.user.rank-user');
    Route::get('friends','UserController@myFriends')->name('web.user.friends');
    Route::delete('friend/remove/{id}','UserController@unFriend')->name('web.user.unfriend');
    Route::get('friend-request','UserController@friendRequest')->name('web.user.friend-request');
    Route::post('friend-request/{id}/{type}','UserController@friendRequestAction')->name('web.user.friend-request.action');

    Route::post('add-friend','UserController@addRelation')->name('web.user.add-friend');
    Route::post('remove-friend','UserController@removeRelation')->name('web.user.remove-friend');

    Route::post('follow','UserController@addRelation')->name('web.user.follow');
    Route::post('unfollow','UserController@removeRelation')->name('web.user.unfollow');

    Route::get('followers','UserController@followers')->name('web.user.followers');
    Route::post('following-action/{id}','UserController@followingAction')->name('web.user.following-action');
});
