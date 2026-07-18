<?php

use App\Http\Controllers\Frontend\ReviewController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderManagerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportModuleController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\StoreManagerController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\RatingController;
use App\Http\Controllers\Auth\RegisterAdminController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PopupController;

// Route::get('/', function () {
//     return view('welcome');
// });

//Facebook
// Route::get('auth/facebook', [FacebookController::class, 'loginUsingFacebook'])->name('login');
// Route::get('facebook/callback', [FacebookController::class, 'callbackFromFacebook'])->name('callback');

//Google
// Route::get('auth/google', [GoogleSocialiteController::class, 'redirectToGoogle']);  // redirect to google login
// Route::get('callback/google', [GoogleSocialiteController::class, 'handleCallback']);    // callback route after google account chosen

Route::controller(SocialiteController::class)->group(function(){
Route::get('auth/redirection/{provider}', 'authProviderRedirect')->name('auth.redirection');
Route::get('auth/{provider}/callback/', 'socialAuthentication')->name('auth.callback');
});

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('about', [FrontendController::class, 'about'])->name('about');
Route::get('returnshipping', [FrontendController::class, 'returnshipping'])->name('returnshipping');
Route::get('faq', [FrontendController::class, 'faq'])->name('faq');


Route::get('category', [FrontendController::class, 'category']);
Route::get('view-category/{slug}', [FrontendController::class, 'viewcategory']);
Route::get('category/{cate_slug}/{prod_slug}', [FrontendController::class, 'productview']);

Route::get('product-list', [FrontendController::class, 'productlistAjax']);

Route::post('searchproduct', [FrontendController::class, 'searchProduct']);
Auth::routes( [
    'verify' => true
 ]);

Route::get('register-admin', [RegisterAdminController::class, 'showadminRegistrationForm'])->name('register.admin');
Route::post('register-admin', [RegisterAdminController::class, 'register']);;

Route::get('register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [UserController::class, 'register']);

Route::get('load-cart-data', [CartController::class, 'cartcount']);
Route::get('load-wishlist-count', [WishlistController::class, 'wishlistcount']);

Route::post('add-to-cart', [CartController::class, 'addProduct']);
Route::post('delete-cart-item', [CartController::class, 'deleteproduct']);
Route::post('update-cart', [CartController::class, 'updatecart']);

Route::post('add-to-wishlist', [WishlistController::class, 'add']);
Route::post('delete-wishlist-item', [WishlistController::class, 'deleteitem']);

// Public routes for cart and wishlist
Route::get('cart', [CartController::class, 'viewcart'])->name('cart.index');
Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

Route::get('/coupons', [MarketingController::class, 'view'])->name('coupons.view');
Route::get('/coupons/{id}/edit', [MarketingController::class, 'edit'])->name('coupons.edit');
Route::put('/coupons/{id}', [MarketingController::class, 'update'])->name('coupons.update');
Route::delete('/coupons/{id}', [MarketingController::class, 'destroy'])->name('coupons.destroy');


Route::get('/add-coupon', [CouponController::class, 'create'])->name('coupons.create');
Route::post('/add-coupon', [CouponController::class, 'store'])->name('coupons.store');
Route::post('/validate-coupon', [CouponController::class, 'validateCoupon']);
Route::get('/add-coupon', [CouponController::class, 'create'])->name('coupons.create');
Route::post('/add-coupon', [CouponController::class, 'store'])->name('coupons.store');
Route::post('/create-coupon', [CouponController::class, 'create']);
Route::post('/validate-coupon', [CouponController::class, 'validateCoupon']);

// Protected routes for authenticated users
Route::middleware(['auth', 'verified'])->group(function () {


// Route::get('category', [FrontendController::class, 'category']);
// Route::get('view-category/{slug}', [FrontendController::class, 'viewcategory']);
// Route::get('category/{cate_slug}/{prod_slug}', [FrontendController::class, 'productview']);

// Route::get('product-list', [FrontendController::class, 'productlistAjax']);
// Route::post('searchproduct', [FrontendController::class, 'searchProduct']);
Route::get('cart', [CartController::class, 'viewcart'])->name('cart.index');
Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('place-order', [CheckoutController::class, 'placeorder']);
    

    Route::get('my-orders', [UserController::class, 'index']);
    Route::get('view-order/{id}', [UserController::class, 'view']);
    
    Route::post('/cancel-order/{id}', [OrderController::class, 'cancelOrder'])->name('cancel.order');
    Route::get('reorder/{id}', [OrderController::class, 'reorder'])->name('reorder');

    
    Route::post('add-rating', [RatingController::class, 'add']);
    
    Route::get('add-review/{product_slug}/userreview', [ReviewController::class, 'add']);
    Route::post('add-review', [ReviewController::class, 'create']);
    Route::get('edit-review/{product_slug}/userreview', [ReviewController::class, 'edit']);
    Route::put('update-review', [ReviewController::class, 'update']);
    
    Route::get('my-profile', [UserController::class, 'profile'])->name('profile.index');
    Route::put('my-profile', [UserController::class, 'update'])->name('profile.update');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('password.change');
    Route::post('/password/validate', [UserController::class, 'validateCurrentPassword'])->name('password.validate');
    Route::put('/profile/update-email', [UserController::class, 'updateEmail'])->name('profile.updateEmail');

    Route::post('/return-order', [ReturnController::class, 'store'])->name('return.order');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', 'App\Http\Controllers\Admin\FrontendController@index');
    Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
    Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');

    Route::get('/dashboard', [ReportController::class, 'index'])->name('reports.dashboard');
    Route::get('/report', [ReportModuleController::class, 'index'])->name('reports.index');

    Route::get('categories', 'App\Http\Controllers\Admin\CategoryController@index');
    Route::get('add-category', 'App\Http\Controllers\Admin\CategoryController@add');
    Route::post('insert-category', 'App\Http\Controllers\Admin\CategoryController@insert');
    Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
    Route::put('update-category/{id}', [CategoryController::class, 'update']);
    Route::get('delete-category/{id}', [CategoryController::class, 'destroy']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('add-products', [ProductController::class, 'add']);
    Route::post('insert-product', [ProductController::class, 'insert']);
    Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    Route::put('update-product/{id}', [ProductController::class, 'update']);
    // Route::get('delete-product/{id}', [ProductController::class, 'destroy']);
    Route::get('archive-product/{id}', [ProductController::class, 'archive'])->name('archive.product');
    Route::get('unarchive-product/{id}', [ProductController::class, 'unarchive'])->name('unarchive.product');
    Route::get('archived-products', [ProductController::class, 'archivedProducts'])->name('archived.products');


    Route::get('orders', [OrderController::class, 'index']);
    Route::get('admin/view-order/{id}', [OrderController::class, 'view']);
    Route::put('update-order/{id}', [OrderController::class, 'updateorder']);

    Route::get('order-history', [OrderController::class, 'orderhistory']);
     Route::post('/promote-order/{id}', [OrderController::class, 'promoteOrder'])->name('admin.orders.promote');
    Route::post('/demote-order/{id}', [OrderController::class, 'demoteOrder'])->name('admin.orders.demote');


    Route::get('users', [DashboardController::class, 'users']);
    Route::get('view-user/{id}', [DashboardController::class, 'viewuser']);
    Route::post('/change-role/{id}', [UserController::class, 'changeRole'])->name('change.role');
    

    Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
    Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');
    Route::post('/validate-password', [UserController::class, 'validatePassword']);
    Route::post('/email/change', [UserController::class, 'changeEmail'])->name('email.change');
   
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{id}', [ReturnController::class, 'view'])->name('returns.view');
    Route::resource('returns', ReturnController::class);
    
    Route::get('/refunds', [ReturnController::class, 'refundsIndex'])->name('refunds.index');
 Route::get('/refunds/{id}', [ReturnController::class, 'refundView'])->name('refunds.view');
 Route::post('/refunds/update-status/{id}', [ReturnController::class, 'updateRefundStatus'])->name('refunds.update.status');
 Route::post('/refunds/check-status', [ReturnController::class, 'checkRefundStatus'])->name('refunds.checkStatus');
 
 Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
 Route::delete('warehouses/{id}', [ReturnController::class, 'dispose'])->name('warehouses.dispose');
Route::post('warehouses/add/{id}', [ReturnController::class, 'addToStock'])->name('warehouses.add');

    Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
Route::put('/popups/{id}', [PopupController::class, 'update'])->name('popups.update');
Route::get('/admin/popups/edit', [PopupController::class, 'edit'])->name('popups.edit');
Route::put('/admin/popups/update/{id}', [PopupController::class, 'update'])->name('popups.update');
Route::resource('popups', PopupController::class);

});

Route::middleware(['auth', 'role'])->group(function () {
    Route::get('/inventory-dashboard', [InventoryController::class, 'index'])->name('inventory.dashboard');

    Route::get('products', [ProductController::class, 'index']);
    Route::get('add-products', [ProductController::class, 'add']);
    Route::post('insert-product', [ProductController::class, 'insert']);
    Route::get('edit-product/{id}', [ProductController::class, 'edit']);
    Route::put('update-product/{id}', [ProductController::class, 'update']);
    // Route::get('delete-product/{id}', [ProductController::class, 'destroy']);
    Route::get('archive-product/{id}', [ProductController::class, 'archive'])->name('archive.product');
    Route::get('unarchive-product/{id}', [ProductController::class, 'unarchive'])->name('unarchive.product');
    Route::get('archived-products', [ProductController::class, 'archivedProducts'])->name('archived.products');

    // Admin profile routes
    Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
    Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');
    Route::post('/validate-password', [UserController::class, 'validatePassword']);
    Route::post('/email/change', [UserController::class, 'changeEmail'])->name('email.change');
Route::get('/warehouse', [WarehouseController::class, 'index'])->name('warehouse.index');
Route::delete('warehouses/{id}', [ReturnController::class, 'dispose'])->name('warehouses.dispose');
Route::post('warehouses/add/{id}', [ReturnController::class, 'addToStock'])->name('warehouses.add');
    
});

Route::middleware(['auth', 'role'])->group(function () {

        Route::get('/orders-dashboard', [OrderManagerController::class, 'index'])->name('orders.dashboard');
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('admin/view-order/{id}', [OrderController::class, 'view']);
        Route::put('update-order/{id}', [OrderController::class, 'updateorder']);
        Route::get('order-history', [OrderController::class, 'orderhistory']);
        Route::post('/promote-order/{id}', [OrderController::class, 'promoteOrder'])->name('admin.orders.promote');
        Route::post('/demote-order/{id}', [OrderController::class, 'demoteOrder'])->name('admin.orders.demote');
        Route::post('/promote-order/{id}', [OrderController::class, 'promoteOrder'])->name('admin.orders.promote');
        Route::post('/demote-order/{id}', [OrderController::class, 'demoteOrder'])->name('admin.orders.demote');

        // Admin profile routes
        Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
        Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');
        Route::post('/validate-password', [UserController::class, 'validatePassword']);
        Route::post('/email/change', [UserController::class, 'changeEmail'])->name('email.change');
});

Route::middleware(['auth', 'role'])->group(function () {
    Route::get('/marketing-dashboard', [MarketingController::class, 'index'])->name('marketing.dashboard');
    Route::get('/report', [ReportModuleController::class, 'index'])->name('reports.marketing');

    Route::get('/coupons', [MarketingController::class, 'view'])->name('coupons.view');
    Route::get('/coupons/{id}/edit', [MarketingController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{id}', [MarketingController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [MarketingController::class, 'destroy'])->name('coupons.destroy');

    Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
    Route::put('/popups/{id}', [PopupController::class, 'update'])->name('popups.update');
    Route::get('/admin/popups/edit', [PopupController::class, 'edit'])->name('popups.edit');
    Route::put('/admin/popups/update/{id}', [PopupController::class, 'update'])->name('popups.update');
    Route::resource('popups', PopupController::class);

        // Admin profile routes
        Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
        Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');
        Route::post('/validate-password', [UserController::class, 'validatePassword']);
        Route::post('/email/change', [UserController::class, 'changeEmail'])->name('email.change');
    
});

// Store Manager Routes
Route::middleware(['auth', 'role'])->group(function () {
   Route::get('/storemanager-dashboard', [StoreManagerController::class, 'index'])->name('storemanager.dashboard');
   Route::get('/report', [ReportModuleController::class, 'index'])->name('reports.storemanager');
   Route::get('categories', 'App\Http\Controllers\Admin\CategoryController@index');
   Route::get('add-category', 'App\Http\Controllers\Admin\CategoryController@add');
   Route::post('insert-category', 'App\Http\Controllers\Admin\CategoryController@insert');
   Route::get('edit-category/{id}', [CategoryController::class, 'edit']);
   Route::put('update-category/{id}', [CategoryController::class, 'update']);
   Route::get('delete-category/{id}', [CategoryController::class, 'destroy']);
   Route::get('users', [DashboardController::class, 'users']);
   Route::get('view-user/{id}', [DashboardController::class, 'viewuser']);
   Route::post('/change-role/{id}', [UserController::class, 'changeRole'])->name('change.role');

   Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
   Route::get('/returns/{id}', [ReturnController::class, 'view'])->name('returns.view');
   Route::resource('returns', ReturnController::class);
   Route::get('/refunds', [ReturnController::class, 'refundsIndex'])->name('refunds.index');
Route::get('/refunds/{id}', [ReturnController::class, 'refundView'])->name('refunds.view');
Route::post('/refunds/update-status/{id}', [ReturnController::class, 'updateRefundStatus'])->name('refunds.update.status');
Route::post('/refunds/check-status', [ReturnController::class, 'checkRefundStatus'])->name('refunds.checkStatus');

    // Admin profile routes
    Route::get('admin-profile', [UserController::class, 'adminProfile'])->name('profile.admin');
    Route::put('admin-profile', [UserController::class, 'updateAdminProfile'])->name('profile.admin.update');
    Route::post('/validate-password', [UserController::class, 'validatePassword']);
    Route::post('/email/change', [UserController::class, 'changeEmail'])->name('email.change');
});