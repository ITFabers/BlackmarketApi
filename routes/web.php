<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\Auth\AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerCardStatusController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\FooterLinkController;
use App\Http\Controllers\Admin\FooterSocialLinkController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductVariantItemController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SpecificationKeyController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['XSS']], function () {

    Route::get('/', function () {
        return redirect()->route('admin.login');
    })->name('home');
    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {
        // start auth route
        Route::get('login', [AdminLoginController::class, 'adminLoginPage'])->name('auth-login');
        Route::post('login', [AdminLoginController::class, 'storeLogin'])->name('login');
        Route::post('logout', [AdminLoginController::class, 'adminLogout'])->name('logout');
        Route::post('new-password', [AdminLoginController::class, 'newPassword'])->name('set_new_password');
        Route::get('forget-password', [AdminForgotPasswordController::class, 'forgetPassword'])->name('forget-password');
        Route::post('send-forget-password', [AdminForgotPasswordController::class, 'sendForgetEmail'])->name('send.forget.password');
        Route::get('reset-password/{token}', [AdminForgotPasswordController::class, 'resetPassword'])->name('reset.password');
        Route::post('password-store/{token}', [AdminForgotPasswordController::class, 'storeResetData'])->name('store.reset.password');
        // end auth route

        Route::get('/', [DashboardController::class, 'dashobard'])->name('admin-dashboard');
        Route::get('dashboard', [DashboardController::class, 'dashobard'])->name('dashboard');
        Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('profile-update', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::resource('product-category', ProductCategoryController::class);
        Route::resource('notifications', NotificationController::class);
        Route::post('mark-notification-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark.notification.read');

        Route::put('product-category-status/{id}', [ProductCategoryController::class, 'changeStatus'])->name('product.category.status');

        Route::resource('customer-card-status', CustomerCardStatusController::class);

        Route::resource('product-brand', ProductBrandController::class);
        Route::put('product-brand-status/{id}', [ProductBrandController::class, 'changeStatus'])->name('product.brand.status');

        Route::resource('specification-key', SpecificationKeyController::class);
        Route::put('specification-key-status/{id}', [SpecificationKeyController::class, 'changeStatus'])->name('specification-key.status');

        Route::resource('product', ProductController::class);
        Route::get('create-product-info', [ProductController::class, 'create'])->name('create-product-info');
        Route::put('product-status/{id}', [ProductController::class, 'changeStatus'])->name('product.status');
        Route::put('product-approved/{id}', [ProductController::class, 'productApproved'])->name('product-approved');
        Route::put('removed-product-exist-specification/{id}', [ProductController::class, 'removedProductExistSpecification'])->name('removed-product-exist-specification');
        Route::get('get-variant-items', [ProductController::class, 'getVariantItems'])->name('get-variant-items');

        Route::resource('product-variant', ProductVariantController::class);

        Route::get('product-variant/{id}', [ProductVariantController::class, 'index'])->name('product-variant');
        Route::get('create-product-variant/{id}', [ProductVariantController::class, 'create'])->name('create-product-variant');
        Route::post('store-product-variant', [ProductVariantController::class, 'store'])->name('store-product-variant');
        Route::get('get-product-variant/{id}', [ProductVariantController::class, 'show'])->name('get-product-variant');
        Route::put('update-product-variant/{id}', [ProductVariantController::class, 'update'])->name('update-product-variant');
        Route::delete('delete-product-variant/{id}', [ProductVariantController::class, 'destroy'])->name('delete-product-variant');
        Route::put('product-variant-status/{id}', [ProductVariantController::class, 'changeStatus'])->name('product-variant.status');

        Route::get('product-variant-item', [ProductVariantItemController::class, 'index'])->name('product-variant-item');
        Route::get('create-product-variant-item/{id}', [ProductVariantItemController::class, 'create'])->name('create-product-variant-item');
        Route::post('store-product-variant-item', [ProductVariantItemController::class, 'store'])->name('store-product-variant-item');
        Route::get('edit-product-variant-item/{id}', [ProductVariantItemController::class, 'edit'])->name('edit-product-variant-item');
        Route::get('get-product-variant-item/{id}', [ProductVariantItemController::class, 'show'])->name('egetdit-product-variant-item');

        Route::put('update-product-variant-item/{id}', [ProductVariantItemController::class, 'update'])->name('update-product-variant-item');
        Route::delete('delete-product-variant-item/{id}', [ProductVariantItemController::class, 'destroy'])->name('delete-product-variant-item');
        Route::put('product-variant-item-status/{id}', [ProductVariantItemController::class, 'changeStatus'])->name('product-variant-item.status');


        Route::get('product-gallery/{id}', [ProductGalleryController::class, 'index'])->name('product-gallery');
        Route::post('store-product-gallery', [ProductGalleryController::class, 'store'])->name('store-product-gallery');
        Route::delete('delete-product-image/{id}', [ProductGalleryController::class, 'destroy'])->name('delete-product-image');
        Route::put('product-gallery-status/{id}', [ProductGalleryController::class, 'changeStatus'])->name('product-gallery.status');

        Route::resource('terms-and-condition', TermsAndConditionController::class);
        Route::resource('privacy-policy', PrivacyPolicyController::class);

        Route::resource('blog-category', BlogCategoryController::class);
        Route::put('blog-category-status/{id}', [BlogCategoryController::class, 'changeStatus'])->name('blog.category.status');

        Route::resource('blog', BlogController::class);
        Route::put('blog-status/{id}', [BlogController::class, 'changeStatus'])->name('blog.status');

        Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber');
        Route::delete('delete-subscriber/{id}', [SubscriberController::class, 'destroy'])->name('delete-subscriber');
        Route::post('specification-subscriber-email/{id}', [SubscriberController::class, 'specificationSubscriberEmail'])->name('specification-subscriber-email');
        Route::post('each-subscriber-email', [SubscriberController::class, 'eachSubscriberEmail'])->name('each-subscriber-email');

        Route::put('update-logo-favicon', [SettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');

        Route::resource('admin', AdminController::class);
        Route::put('admin-status/{id}', [AdminController::class, 'changeStatus'])->name('admin-status');

        Route::resource('faq', FaqController::class);
        Route::put('faq-status/{id}', [FaqController::class, 'changeStatus'])->name('faq-status');

        Route::resource('admin-list', AdminUsersController::class);

        Route::get('customer-list', [CustomerController::class, 'index'])->name('customer-list');
        Route::get('customer-show/{id}', [CustomerController::class, 'show'])->name('customer-show');
        Route::put('customer-status/{id}', [CustomerController::class, 'changeStatus'])->name('customer-status');
        Route::delete('customer-delete/{id}', [CustomerController::class, 'destroy'])->name('customer-delete');
        Route::get('pending-customer-list', [CustomerController::class, 'pendingCustomerList'])->name('pending-customer-list');
        Route::get('send-email-to-all-customer', [CustomerController::class, 'sendEmailToAllUser'])->name('send-email-to-all-customer');
        Route::post('send-mail-to-all-user', [CustomerController::class, 'sendMailToAllUser'])->name('send-mail-to-all-user');
        Route::post('send-mail-to-single-user/{id}', [CustomerController::class, 'sendMailToSingleUser'])->name('send-mail-to-single-user');

        Route::get('topbar-contact', [ContentController::class, 'headerPhoneNumber'])->name('topbar-contact');
        Route::put('update-topbar-contact', [ContentController::class, 'updateHeaderPhoneNumber'])->name('update-topbar-contact');

        Route::get('product-quantity-progressbar', [ContentController::class, 'productProgressbar'])->name('product-quantity-progressbar');
        Route::put('update-product-quantity-progressbar', [ContentController::class, 'updateProductProgressbar'])->name('update-product-quantity-progressbar');

        Route::get('default-avatar', [ContentController::class, 'defaultAvatar'])->name('default-avatar');
        Route::post('update-default-avatar', [ContentController::class, 'updateDefaultAvatar'])->name('update-default-avatar');

        Route::get('shop-page', [ContentController::Class, 'shopPage'])->name('shop-page');
        Route::put('update-filter-price', [ContentController::Class, 'updateFilterPrice'])->name('update-filter-price');

        Route::resource('city', CityController::class);
        Route::put('city-status/{id}', [CityController::class, 'changeStatus'])->name('city-status');


        Route::resource('slider', SliderController::class);
        Route::put('slider-status/{id}', [SliderController::class, 'changeStatus'])->name('slider-status');

        Route::get('all-order', [OrderController::class, 'index'])->name('all-order');
        Route::get('pending-order', [OrderController::class, 'pendingOrder'])->name('pending-order');
        Route::get('pregress-order', [OrderController::class, 'pregressOrder'])->name('pregress-order');
        Route::get('delivered-order', [OrderController::class, 'deliveredOrder'])->name('delivered-order');
        Route::get('completed-order', [OrderController::class, 'completedOrder'])->name('completed-order');
        Route::get('declined-order', [OrderController::class, 'declinedOrder'])->name('declined-order');
        Route::get('cash-on-delivery', [OrderController::class, 'cashOnDelivery'])->name('cash-on-delivery');
        Route::get('order-show/{id}', [OrderController::class, 'show'])->name('order-show');
        Route::delete('delete-order/{id}', [OrderController::class, 'destroy'])->name('delete-order');
        Route::put('update-order-status/{id}', [OrderController::class, 'updateOrderStatus'])->name('update-order-status');

        Route::resource('coupon', CouponController::class);
        Route::put('coupon-status/{id}', [CouponController::class, 'changeStatus'])->name('coupon-status');

        Route::resource('footer', FooterController::class);
        Route::resource('social-link', FooterSocialLinkController::class);
        Route::resource('footer-link', FooterLinkController::class);
        Route::get('second-col-footer-link', [FooterLinkController::class, 'secondColFooterLink'])->name('second-col-footer-link');
        Route::get('third-col-footer-link', [FooterLinkController::class, 'thirdColFooterLink'])->name('third-col-footer-link');
        Route::put('update-col-title/{id}', [FooterLinkController::class, 'updateColTitle'])->name('update-col-title');

        Route::get('admin-language', [LanguageController::class, 'adminLnagugae'])->name('admin-language');
        Route::post('update-admin-language', [LanguageController::class, 'updateAdminLanguage'])->name('update-admin-language');

        Route::get('admin-validation-language', [LanguageController::class, 'adminValidationLnagugae'])->name('admin-validation-language');
        Route::post('update-admin-validation-language', [LanguageController::class, 'updateAdminValidationLnagugae'])->name('update-admin-validation-language');

        Route::get('website-language', [LanguageController::class, 'websiteLanguage'])->name('website-language');
        Route::post('update-language', [LanguageController::class, 'updateLanguage'])->name('update-language');

    });
});

// start admin routes
