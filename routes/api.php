<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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


Route::group(['namespace' => 'Api'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forget-password', 'AuthController@forgetPassword');
    Route::post('confirm-token', 'AuthController@confirmToken');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::get('deal-notification', 'AuthController@dealNotification');
    Route::get('all-notification', 'AuthController@allNotification');

    // Category
    Route::get('category','ProductController@getCategory');
    Route::get('subcategory/{category_id}','ProductController@getSubCategory');
    Route::get('product/{subcategory_id}','ProductController@getProduct');

    Route::get('most-selling','ProductController@getMostSellingProducts');
    Route::get('deal','ProductController@deal');

    Route::get('home','ProductController@home');


    Route::post('location','ProductController@getlocation');

    Route::middleware('auth:sanctum')->group(function () {
        // return $request->user();

        Route::post('logout','AuthController@logout');
        Route::post('change-password','AuthController@changePassword');
        Route::get('edit-profile','AuthController@editProfile');
        Route::post('update-profile','AuthController@updateProfile');





        Route::get('single-product/{id}','ProductController@singleProduct');

        Route::get('pharmacy/{id}','ProductController@getPharmacy');

        Route::post('post-order/{id}','OrderController@postOrder');

        Route::get('coupon','OrderController@getCoupon');










    });
});

