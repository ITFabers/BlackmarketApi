<?php

use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\API\AuthController as Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\User\AddressCotroller;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;


// new routes


//old routes


//new routes
Route::group(['middleware' => ['XSS']], function () {
	Route::get('/main', [StaticController::class, 'index'])->name('index');
	Route::get('/categories/{page?}', [StaticController::class, 'categories'])->name('categories');
	Route::get('/category/{id}', [StaticController::class, 'category'])->name('category');
	Route::get('/brands/{page?}', [StaticController::class, 'brands'])->name('brands');
	Route::get('/brand/{slug}/{page?}', [StaticController::class, 'brand'])->name('brand');
	Route::get('/products/{type}/{page?}', [StaticController::class, 'products'])->name('products');
  Route::get('/search-product', [HomeController::class, 'product'])->name('search-product');

	Route::group([
	    'prefix' => 'auth'

	], function () {
			Route::controller(Auth::class)->group(function () {
		    Route::match(['post'],'login', 'login');
		    Route::post('register', 'register');
		    Route::post('resend-token', 'resendVerificationToken');
		    Route::post('activate-account', 'activateAccount')->name('activate');
		    Route::post('password-reset', 'sendResetLinkEmail');
		    Route::post('change-password', 'reset')->name('reset');
		});
	});
});

Route::post('send-email', [EmailController::class, 'course']);
Route::post('send-contact-email', [EmailController::class, 'contact']);

Route::post('/store-register', [RegisterController::class, 'storeRegister'])->name('store-register');
Route::get('/login', [LoginController::class, 'loginPage'])->name('login');
Route::post('/store-login', [LoginController::class, 'storeLogin'])->name('store-login');
Route::post('/resend-register-code', [RegisterController::class, 'resendRegisterCode'])->name('resend-register-code');
Route::get('/user-verification/{token}', [RegisterController::class, 'userVerification'])->name('user-verification');
Route::get('/forget-password', [LoginController::class, 'forgetPage'])->name('forget-password');
Route::post('/send-forget-password', [LoginController::class, 'sendForgetPassword'])->name('send-forget-password');
Route::get('/reset-password/{token}', [LoginController::class, 'resetPasswordPage'])->name('reset-password');
Route::post('/store-reset-password/{token}', [LoginController::class, 'storeResetPasswordPage'])->name('store-reset-password');
Route::get('/user/logout', [LoginController::class, 'userLogout'])->name('user.logout');

Route::group(['middleware' => ['XSS']], function () {

Route::group([], function () {
  Route::get('/homepage', [HomeController::class, 'homepage'])->name('homepage');
  // Route::get('/categories', [HomeController::class, 'categories'])->name('categories');

    Route::get('/website-setup', [HomeController::class, 'websiteSetup'])->name('website-setup');
    Route::get('/wishlist',[HomeController::class,'wishlist'])->name('wishlist');
    Route::get('/partners',[HomeController::class,'partners'])->name('partners');

    Route::get('/', [HomeController::class, 'index']);
    Route::post('/send-contact-message', [HomeController::class, 'sendContactMessage'])->name('send-contact-message');
    Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog-detail');
    Route::post('/blog-comment', [HomeController::class, 'blogComment'])->name('blog-comment');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsAndCondition'])->name('terms-and-conditions');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::post('subscribe-request', [HomeController::class, 'subscribeRequest'])->name('subscribe-request');
    Route::get('subscriber-verification/{email}/{token}', [HomeController::class, 'subscriberVerifcation'])->name('subscriber-verification');
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::get('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::get('/cart-clear', [CartController::class, 'cartClear'])->name('cart-clear');
    Route::get('/cart-item-remove/{id}', [CartController::class, 'cartItemRemove'])->name('cart-item-remove');
    Route::get('/cart-item-increment/{id}', [CartController::class, 'cartItemIncrement'])->name('cart-item-increment');
    Route::get('/cart-item-decrement/{id}', [CartController::class, 'cartItemDecrement'])->name('cart-item-decrement');

    Route::get('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply-coupon');
    Route::get('/calculate-product-price', [CartController::class, 'calculateProductPrice'])->name('calculate-product-price');
    Route::group(['as'=> 'user.', 'prefix' => 'user'],function (){
        Route::get('dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('order', [UserProfileController::class, 'order'])->name('order');
        Route::get('pending-order', [UserProfileController::class, 'pendingOrder'])->name('pending-order');
        Route::get('complete-order', [UserProfileController::class, 'completeOrder'])->name('complete-order');
        Route::get('declined-order', [UserProfileController::class, 'declinedOrder'])->name('declined-order');
        Route::get('order-show/{id}', [UserProfileController::class, 'orderShow'])->name('order-show');
        Route::get('review', [UserProfileController::class, 'review'])->name('review');
        Route::get('get-review/{id}', [UserProfileController::class, 'showReview'])->name('show-review');
        Route::get('my-profile', [UserProfileController::class, 'myProfile'])->name('my-profile');
        Route::post('update-profile', [UserProfileController::class, 'updateProfile'])->name('update-profile');
        // Route::get('address', [UserProfileController::class, 'address'])->name('address');
        Route::post('update-password', [UserProfileController::class, 'updatePassword'])->name('update-password');
        Route::resource('address', AddressCotroller::class);
        Route::get('wishlist', [UserProfileController::class, 'wishlist'])->name('wishlist');
        Route::get('add-to-wishlist/{id}', [UserProfileController::class, 'addToWishlist'])->name('add-to-wishlist');
        Route::get('remove-wishlist/{id}', [UserProfileController::class, 'removeWishlist'])->name('remove-wishlist');
        Route::get('clear-wishlist', [UserProfileController::class, 'clearWishlist'])->name('clear-wishlist');
        Route::post('product-report', [UserProfileController::class, 'storeProductReport'])->name('product-report');

        Route::post('update-review/{id}', [UserProfileController::class, 'updateReview'])->name('update-review');

        Route::delete('remove-account', [UserProfileController::class, 'remove_account'])->name('remove-account');
        Route::group(['as'=> 'checkout.', 'prefix' => 'checkout'],function (){
            Route::get('/', [CheckoutController::class, 'checkout'])->name('checkout');

            Route::post('/cash-on-delivery', [PaymentController::class, 'cashOnDelivery'])->name('cash-on-delivery');
        });
    });
}); });
