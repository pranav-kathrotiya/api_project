<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminLocationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\GiveawayBannerController;
use App\Http\Controllers\GivewaysController;
use App\Http\Controllers\OfferBannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/change-password', [AuthController::class, 'change_password']);
// Route::post('/send-otp', [AuthController::class, 'sendOtp']);
// Route::post('/login-with-otp', [AuthController::class, 'loginWithOtp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/address', [AddressController::class, 'address']);
    Route::post('/user/addresses', [AddressController::class, 'getUserAddresses']);

    Route::post('/add-category', [CategoryController::class, 'add']);
    Route::get('/get-category', [CategoryController::class, 'get']);

    Route::post('/add-product', [ProductController::class, 'add']);
    Route::get('/get-product', [ProductController::class, 'get']);
    Route::get('/search-product', [ProductController::class, 'searchProduct']);

    Route::get('/get-user-id', [UserController::class, 'getUserById']);
    Route::post('/edit-user', [UserController::class, 'editUser']);
    Route::post('/delete-account', [UserController::class, 'deleteAccount']);

    Route::get('/get-all-user', [UserController::class, 'getAllUser']);

    Route::post('/add-to-wishlist', [WishlistController::class, 'addToWishlist']);
    Route::post('/remove-from-wishlist', [WishlistController::class, 'removeFromWishlist']);
    Route::get('/get-wishlist', [WishlistController::class, 'getWishlist']);

    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/remove-from-cart', [CartController::class, 'removeFromCart']);
    Route::get('/get-cart', [CartController::class, 'getCart']);
    /*-----------------------------------------------------------------------------------*/
    Route::post('/placeOrder', [OrderController::class, 'placeOrder']);
    Route::get('/get-all-orders', [OrderController::class, 'getallorders']);
    Route::get('/get-order-details', [OrderController::class, 'getorderdetails']);
    Route::get('/order-status-update', [OrderController::class, 'orderstatusupdate']);

    Route::get('/giveway-users-list', [GivewaysController::class, 'givewayuserslist']);
    Route::get('/get-giveway', [GivewaysController::class, 'getgiveway']);
    Route::post('/add-update', [GivewaysController::class, 'addOrupdate']);
    Route::post('/delete-giveaway', [GivewaysController::class, 'deletegiveaway']);
    Route::post('/add-giveway-prize', [GivewaysController::class, 'addgivewayprize']);
    Route::post('/delete-prize', [GivewaysController::class, 'deleteprize']);

    Route::post('/send-notification', [FcmController::class, 'sendNotification']);
    /*-----------------------------------------------------------------------------------*/

    Route::post('/add-offer-banner', [OfferBannerController::class, 'addOfferBanner']);
    Route::post('/edit-offer-banner', [OfferBannerController::class, 'editOfferBanner']);
    Route::get('/get-offer-banner', [OfferBannerController::class, 'getOfferBanner']);
    Route::get('/delete-offer-banner', [OfferBannerController::class, 'deleteOfferBanner']);

    Route::post('/add-giveaway-banner', [GiveawayBannerController::class, 'addGiveawayBanner']);
    Route::post('/edit-giveaway-banner', [GiveawayBannerController::class, 'editGiveawayBanner']);
    Route::get('/get-giveaway-banner', [GiveawayBannerController::class, 'getGiveawayBanner']);
    Route::get('/delete-giveaway-banner', [GiveawayBannerController::class, 'deleteGiveawayBanner']);

    Route::post('/add-admin-location', [AdminLocationController::class, 'addOrUpdate']);
    Route::get('/delete-admin-location', [AdminLocationController::class, 'delete']);
    Route::get('/list-admin-location', [AdminLocationController::class, 'list']);
});
