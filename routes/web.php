<?php

use App\Http\Controllers\Admin\AboutusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\PharmacyProductController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductPhotoController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalRequestController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

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
Route::get('/cache_clear', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    return 'Application cache cleared!';
});
Route::get('/schedule', function () {
    Artisan::call('schedule:work');
    return 'Application schedule work!';
});
/*
Admin routes
 * */
Route::get('/admin', [AuthController::class, 'getLoginPage']);
Route::post('admin/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard']);
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::post('update-password', [AdminController::class, 'profile_change_password'])->name('profile.change-password');
    Route::get('logout', [AdminController::class, 'logout']);
    /**officer */
    Route::get('user/status/{id}', [UserController::class, 'status'])->name('officer.status');
    /**company */
    Route::get('pharmacy/status/{id}', [PharmacyController::class, 'status'])->name('pharmacy.status');

    /** resource controller */

    Route::resource('user', UserController::class);
    Route::resource('pharmacy', PharmacyController::class);
    Route::get('countryVise', [PharmacyController::class, 'getCountry'])->name('countryVise.create');
    // Route::post('get-states-by-country', [PharmacyController::class, 'getState']);
    // Route::post('get-cities-by-state', [PharmacyController::class, 'getCity']);
    Route::get('view-account-detail', [PharmacyController::class, 'accountDetail'])->name('accountDetail.index');

    // Pharmacy Product
    Route::get('pharmacyProduct/{id}', [PharmacyProductController::class, 'index'])->name('pharmacyProduct.index');
    Route::get('pharmacyProduct-create/{id}', [PharmacyProductController::class, 'create'])->name('pharmacyProduct.create');
    Route::post('pharmacyProduct-store/{id}', [PharmacyProductController::class, 'store'])->name('pharmacyProduct.store');
    Route::post('get-subcategory-by-categories', [PharmacyProductController::class, 'getSubCategory']);
    Route::post('get-product-by-subcategory', [PharmacyProductController::class, 'getProduct']);
    Route::get('pharmacyProduct-delete/{id}', [PharmacyProductController::class, 'destroy'])->name('pharmacyProduct.destroy');
    Route::get('pharmacyProduct-edit/{id}', [PharmacyProductController::class, 'edit'])->name('pharmacyProduct.edit');
    Route::post('pharmacyProduct-update/{id}', [PharmacyProductController::class, 'update'])->name('pharmacyProduct.update');

    Route::get('pharmacy_productPhoto/{id}/{product_id}', [PharmacyProductController::class, 'productPhoto_index'])->name('pharmacy_productPhoto.index');

    // withdrawal Request
    Route::get('withDrawalRequest-index', [WithdrawalRequestController::class, 'index'])->name('withDrawalRequest.index');
    Route::post('withDrawalRequest/status/{id}', [WithdrawalRequestController::class, 'status'])->name('withDrawalRequest.status');
    Route::get('withdrawal-account-detail', [WithdrawalRequestController::class, 'accountDetail'])->name('withdrawalAccountDetail.index');
    // Route::get('notification', [WithdrawalRequestController::class, 'notification'])->name('notification');
    Route::get('notification-create', [NotificationController::class, 'create'])->name('notification.create');
    Route::post('notification-store', [NotificationController::class, 'store'])->name('notification.store');
    Route::get('notification', [NotificationController::class, 'getnotification'])->name('getnotification');
    Route::get('url-notification', [NotificationController::class, 'urlNotification'])->name('urlNotification');
    Route::post('markAllRead', [NotificationController::class, 'markAllRead'])->name('markAllRead');


    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubCategoryController::class);

    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::get('view-order-detail', [OrderController::class, 'orderDetail']);
    //report
    Route::get('report/index', [ReportController::class, 'index'])->name('report.index');
    Route::post('checkReport', [ReportController::class, 'checkReport'])->name('report.check');

    Route::resource('product', ProductController::class);
    Route::get('product/status/{id}', [ProductController::class, 'status'])->name('product.status');
    Route::get('view-discount', [ProductController::class, 'discount']);
    Route::post('update-discount', [ProductController::class, 'updateDiscount'])->name('update-discount');


    Route::post('get-subcategory-by-category', [ProductController::class, 'getSubCategory']);
    Route::get('product-photo/{id}', [ProductPhotoController::class, 'index'])->name('product-photo.index');
    Route::get('product-photo-create/{id}', [ProductPhotoController::class, 'create'])->name('product-photo.create');
    Route::post('product-photo-store/{id}', [ProductPhotoController::class, 'store'])->name('product-photo.store');
    Route::get('product-photo-edit/{id}', [ProductPhotoController::class, 'edit'])->name('product-photo.edit');
    Route::post('product-photo-update/{id}', [ProductPhotoController::class, 'update'])->name('product-photo.update');
    Route::delete('product-photo-destroy/{id}', [ProductPhotoController::class, 'destroy'])->name('product-photo.destroy');

    //Commission
    Route::get('commission', [CommissionController::class, 'index'])->name('commission.index');
    Route::get('commission-edit/{id}', [CommissionController::class, 'edit'])->name('commission.edit');
    Route::post('commission-update/{id}', [CommissionController::class, 'update'])->name('commission.update');

    Route::resource('banner', BannerController::class);
    Route::resource('about', AboutusController::class);
    Route::resource('policy', PolicyController::class);
    Route::resource('terms', TermConditionController::class);
    Route::resource('faq', FaqController::class);

});

Route::group(['namespace' => 'App\Http\Controllers\Pharmacy'], function () {
    Route::get('/', function () {
        return redirect('login');
    });
    Route::view('login', 'pharmacy.auth.login')->name('View_login');
    Route::view('register', 'pharmacy.auth.register')->name('register');
    Route::view('forget-password', 'pharmacy.auth.forgetPassword')->name('forgot_password');
    Route::get('change_password/{id}', 'AuthController@change_password');
    Route::post('register', 'AuthController@register')->name('pharmacy.register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('reset-password', 'AuthController@resetPassword')->name('reset_password');
    Route::post('change-password', 'AuthController@changePassword')->name('change_password');

});

Route::group(['prefix' => 'pharmacy', 'namespace' => 'App\Http\Controllers\Pharmacy', 'middleware' => 'pharmacy', 'as' => 'pharmacy.'], function () {
    Route::get('dashboard', 'PharmacyController@index')->name('dashboard');
    Route::get('profile', 'PharmacyController@getProfile')->name('profile');
    Route::post('update-profile/{id}', 'PharmacyController@updateProfile')->name('updateProfile');
    Route::post('update-password', 'PharmacyController@profile_change_password')->name('change-password');
    // Route::post('get-states-by-country', 'PharmacyController@getState');
    // Route::post('get-cities-by-state', 'PharmacyController@getCity');
    Route::get('logout', 'PharmacyController@logout')->name('logout');

    //Product
    Route::resource('product', 'ProductController');
    Route::post('get-subcategory-by-categories', 'ProductController@getSubCategory');
    Route::post('get-product-by-subcategory', 'ProductController@getProduct');
    Route::get('view-discount', 'ProductController@discount');
    //product Photo
    Route::get('productPhoto/{id}/{product_id}', 'ProductController@productPhoto_index')->name('productPhoto.index');

    //Account
    // Route::put('update/{id}', 'BankDetailController@update')->name('bank_detail.update');
    Route::get('withDrawal', 'WithDrawalController@index')->name('withDrawal.index');
    Route::post('withDrawal-store', 'WithDrawalController@store')->name('withdrawal.store');
    // Order
    Route::get('order', 'OrderController@index')->name('order.index');
    Route::post('order/status/{id}', 'OrderController@status')->name('order.status');
    Route::get('view-order-detail', 'OrderController@orderDetail');

    Route::get('notification', 'NotificationController@getnotification')->name('notification');
    Route::get('url-notification', 'NotificationController@urlNotification')->name('urlNotification');
    Route::post('markAllRead', 'NotificationController@markAllRead')->name('markAllRead');


    Route::resource('bank_detail', 'BankDetailController');

    Route::get('report/index', 'ReportController@index')->name('report.index');
    Route::post('checkReport', 'ReportController@checkReport')->name('report.check');



    Route::get('about', 'SecurityController@aboutUs')->name('about.index');
    Route::get('policy', 'SecurityController@privacyPolicy')->name('policy.index');
    Route::get('terms', 'SecurityController@termCondition')->name('terms.index');
    Route::get('faq', 'SecurityController@faq')->name('faq.index');

});
