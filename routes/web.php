<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\AdminLogoController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductItemController;
use App\Http\Controllers\admin\SliderController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductReviewController;
use App\Http\Controllers\admin\ProductVariatonController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\WeightPrice;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     OrderEmail(2);
// });

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.add-to-cart');
Route::post('/add-item-to-cart', [CartController::class, 'addItemToCart'])->name('front.item.add-to-cart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.process.checkout');
Route::get('/thanks/{orderId}/', [CartController::class, 'thankyou'])->name('front.thankyou');
Route::post('/get-order-summery', [CartController::class, 'getOrderSummery'])->name('front.getOrderSummery');
Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class, 'removeCoupon'])->name('front.removeCoupon');
Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::post('/add-to-item-wishlist', [FrontController::class, 'addToItemWishlist'])->name('front.addToItemWishlist');
Route::post('/send-contact-email', [FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');

//for user registration and login
Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');

        Route::get('/register', [AuthController::class, 'register'])->name('front.account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');

        Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('front.forgotPassword');
        Route::post('/process-forgot-password', [AuthController::class, 'processForgotPassword'])->name('front.processForgotPassword');
        Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('front.resetPassword');
        // Route::post('/apply-discount',[CartController::class,'applyDiscount'])->name('front.applyDiscount');
        Route::post('/process-reset-password', [AuthController::class, 'processResetPassword'])->name('front.processResetPassword');
        Route::post('/save-rating/{productId}', [ShopController::class, 'saveRating'])->name('front.saveRating');




        //opt verification
        Route::get('/otp-login', [AuthController::class, 'otpLogin'])->name('account.otpLogin');
        Route::post('/otp-generate', [AuthController::class, 'generateOtp'])->name('account.generateOtp');
        Route::get('/otp-verify/{id}', [AuthController::class, 'otpVerify'])->name('account.otpVerify');
        Route::get('/otp-verify-forgetPassword/{id}', [AuthController::class, 'otpVerifyForgetPassword'])->name('account.otpVerifyForgetPassword');
        Route::post('/otp-register', [AuthController::class, 'otpRegister'])->name('account.otpRegister');
        Route::get('/otp-changePassword', [AuthController::class, 'otpChangePassword'])->name('account.otpChangePassword');
        Route::post('/otp-changePassword', [AuthController::class, 'otpStorePassword'])->name('account.otpStorePassword');
    });
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/order-details/{id}', [AuthController::class, 'orderDetails'])->name('account.orderDetails');
        Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishList'])->name('account.removeProductFromWishList');
        Route::get('/my-orders/pdf/{id}', [AuthController::class, 'userDownloadPdf'])->name('front.account.pdf');
        Route::put('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::put('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
    });
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('account.showChangePasswordForm');
    Route::post('/process-change-password', [AuthController::class, 'changePassword'])->name('account.processChangePassword');

    Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
});

Route::group(['prefix' => 'admin'], function () {
    // Redirect /admin to /admin/login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        /*slider*/
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{sliders}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::put('/sliders/{sliders}', [SliderController::class, 'update'])->name('sliders.update');
        Route::delete('/sliders/{sliders}', [SliderController::class, 'destroy'])->name('sliders.delete');

        /*category*/
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

        /*sub_category*/
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        /*products route*/
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');

        Route::post('/products-subCategories', [ProductSubCategoryController::class, 'index'])->name('products-subCategories.index');
        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');

        //Product items routes
        Route::get('/productvariation/create/{id}', [ProductItemController::class, 'create'])->name('productvariation.create');
        Route::post('/productvariation/store', [ProductItemController::class, 'store'])->name('productvariation.store');

        //Brands Routes
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brands}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brands}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brands}', [BrandController::class, 'destroy'])->name('brands.delete');

        //coupon code routes
        Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [DiscountCodeController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [DiscountCodeController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [DiscountCodeController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [DiscountCodeController::class, 'destroy'])->name('coupons.delete');

        // Order routes
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::get('/orders/pdf/{id}', [OrderController::class, 'downloadPdf'])->name('orders.download.pdf');
        Route::post('/orders/change-status/{id}', [OrderController::class, 'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/orders/send-email/{id}', [OrderController::class, 'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');

        // pages routes
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.delete');

        //Shipping routes
        Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');

        //users routes
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/admin-users', [UserController::class, 'admin_index'])->name('users.admin_index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{users}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{users}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{users}', [UserController::class, 'destroy'])->name('users.delete');

        //Weight routes
        Route::get('/weights', [WeightPrice::class, 'index'])->name('weights.list');
        Route::get('/weights/create', [WeightPrice::class, 'create'])->name('weights.create');
        Route::post('/weights', [WeightPrice::class, 'store'])->name('weights.store');
        Route::get('/weights/{id}', [WeightPrice::class, 'edit'])->name('weights.edit');
        Route::put('/weights/{id}', [WeightPrice::class, 'update'])->name('weights.update');
        Route::delete('/weights/{id}', [WeightPrice::class, 'destroy'])->name('weights.delete');

        //setting routes
        Route::get('/change-password', [SettingController::class, 'showChangePassword'])->name('admin.showChangePassword');
        Route::post('/process-change-password', [SettingController::class, 'processChangePassword'])->name('admin.processChangePassword');


        //logo route
        Route::get('/logo', [AdminLogoController::class, 'index'])->name('logo.index');
        Route::get('/logo/create', [AdminLogoController::class, 'create'])->name('logo.create');
        Route::post('/logo', [AdminLogoController::class, 'store'])->name('logo.store');
        Route::get('/logo/{logo}/edit', [AdminLogoController::class, 'edit'])->name('logo.edit');
        Route::put('/logo/{logo}', [AdminLogoController::class, 'update'])->name('logo.update');

        //logo route
        Route::get('/product_review', [ProductReviewController::class, 'index'])->name('product_review.index');
        Route::post('/product_review/toggle_status', [ProductReviewController::class, 'toggleStatus'])->name('product_review.toggle_status');

        //  Route::get('/logo/create',[AdminLogoController::class,'create'])->name('logo.create');
        //  Route::post('/logo',[AdminLogoController::class,'store'])->name('logo.store');
        //  Route::get('/logo/{logo}/edit',[AdminLogoController::class,'edit'])->name('logo.edit');
        //  Route::put('/logo/{logo}',[AdminLogoController::class,'update'])->name('logo.update');

        //variation route
        Route::get('/variations', [ProductVariatonController::class, 'index'])->name('variation.index');
        Route::get('/variations/create', [ProductVariatonController::class, 'create'])->name('variation.create');
        Route::get('/variations/{id}/add', [ProductVariatonController::class, 'add'])->name('variation.add');
        Route::post('/variations-store', [ProductVariatonController::class, 'store'])->name('variation.store');
        Route::post('/variations', [ProductVariatonController::class, 'save'])->name('variation.save');
        Route::get('/variations/{variations}/show', [ProductVariatonController::class, 'show'])->name('variation.show');
        Route::get('/variations/{variations}/edit', [ProductVariatonController::class, 'edit'])->name('variation.edit');
        Route::put('/variations/{variations}', [ProductVariatonController::class, 'update'])->name('variation.update');
        Route::delete('/variations/{id}', [ProductVariatonController::class, 'destroy'])->name('variation.delete');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);  // Corrected to Str::slug
            }
            return response()->json([
                'status' => true,
                'slug' => $slug,
            ]);
        })->name('getSlug');
    });
});
