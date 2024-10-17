<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::pattern('id', '\d+');


Route::group(['prefix'=>'user','namespace'=>'User'],function() {





    Route::post('/wishlist', 'UserApiController@Wishlist');
    Route::post('/add-to-wishlist', 'UserApiController@AddToWishlist');
    Route::post('/delete-from-wishlist', 'UserApiController@DeleteFromWishlist');


    Route::post('/my-profile-page', 'UserApiController@myProfilePage');
    Route::post('/profile-page', 'UserApiController@profilePage');
    Route::post('/profile', 'DealApiController@profile');
    Route::post('/my-profile', 'UserApiController@myProfile');
    Route::post('/edit-profile', 'UserApiController@EditProfile');
    Route::post('/edit-profile-action', 'UserApiController@EditProfileAction');
//    Route::post('/change-password', 'UserApiController@changePassword');

    Route::post('/login', 'UserApiController@login');

    Route::post('/check-user', 'UserApiController@checkUser');
    Route::post('/check-register', 'Auth\RegisterUserApiController@CheckRegister');
//    Route::post('/register', 'Auth\RegisterUserApiController@register');
    Route::post('/register', 'UserApiController@register');
    Route::post('/job-attributes', 'UserApiController@job_attributes');
    Route::post('/jobs', 'UserApiController@jobs');

    Route::post('/verify', 'UserApiController@verify')->name('user.verification');

    Route::post('/password/forget', 'Auth\ForgotPasswordUserApiController@sendResetLinkEmail');
    Route::post('/password/verify-reset', 'Auth\ForgotPasswordUserApiController@verifyReset');
    Route::post('/password/reset', 'Auth\ResetPasswordUserApiController@reset');


    /*
     * Socialite
     */
    Route::post('/facebook/callback', 'FacebookApiController@callback')->name('api.user.facebook.callback');
    Route::post('/google/callback', 'GoogleApiController@callback')->name('api.user.google.callback');;


    // stages
    Route::post('/stages', 'UserApiController@stages');
    Route::post('/create-stage', 'UserApiController@createStage');
    Route::post('/update-stage', 'UserApiController@updateStage');



    // UserPanel
    Route::post('/user-info', 'UserInfoApiController@info');
    Route::post('/update-user-info', 'UserInfoApiController@updateInfo');
    Route::post('/change-password', 'UserApiController@changePassword');


    Route::post('/about', 'MiscApiController@about');
    Route::post('/page', 'MiscApiController@page');

    Route::group(['prefix'=>'deal'],function() {

        Route::post('/change-status', 'DealApiController@changeStatus');
        Route::post('/out', 'DealApiController@dealOut');
        Route::post('/in', 'DealApiController@dealIn');
        Route::post('/create', 'DealApiController@create');


    });

    Route::group(['prefix'=>'relations'],function() {

        Route::post('/add', 'RelationsApiController@add');
        Route::post('/remove', 'RelationsApiController@remove');
        Route::post('/remove-relation', 'RelationsApiController@removeRelation');
        Route::post('/change-status', 'RelationsApiController@changeStatus');
        Route::post('/friend-requests', 'RelationsApiController@friendRequests');
        Route::post('/followers', 'RelationsApiController@followers');
        Route::post('/following', 'RelationsApiController@following');
        Route::post('/friends', 'RelationsApiController@friends');


    });

    Route::group(['prefix'=>'items'],function() {
        Route::post('/category-table', 'ItemApiController@categoryTable');
        Route::post('/category-tree', 'ItemApiController@categoryTree');
        Route::post('/sub-categories', 'ItemApiController@subCategories');
        Route::post('/all-items-auth', 'ItemApiController@all_items_auth');
        Route::post('/all-items', 'ItemApiController@all_items');
        Route::post('/item-details-auth', 'ItemApiController@ItemDetailsAuth');
        Route::post('/item-details', 'ItemApiController@ItemDetails');
        Route::post('/like', 'ItemApiController@like');
        Route::post('/share', 'ItemApiController@share');
        Route::post('/comment', 'ItemApiController@comment');
        Route::post('/my-items', 'ItemApiController@my_items');
        Route::post('/create-item-data', 'ItemApiController@create_item_data');
        Route::post('/item-attributes', 'ItemApiController@item_attributes');
        Route::post('/create-item', 'ItemApiController@create_item');
        Route::post('/comments', 'ItemApiController@ItemComments');
        Route::post('/types', 'ItemApiController@ItemTypes');
//        Route::post('/data', 'ItemApiController@ItemData');
        Route::post('/search', 'ItemApiController@search');
        Route::post('/data', 'ItemApiController@item_data');
        Route::post('/edit-item', 'ItemApiController@editItem');
        Route::post('/edit-item-action', 'ItemApiController@editItemAction');
        Route::post('/delete', 'ItemApiController@delete');


    });


    Route::group(['prefix'=>'email'],function() {
        Route::post('/compose', 'EmailApiController@compose');
        Route::post('/inbox', 'EmailApiController@inbox');
        Route::post('/sent', 'EmailApiController@sent');
        Route::post('/to-trash', 'EmailApiController@to_trash');
        Route::post('/trash', 'EmailApiController@trash');
        Route::post('/return-form-trash', 'EmailApiController@return_form_trash');
        Route::post('/delete', 'EmailApiController@delete');

    });

});
