<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfirmationController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SaveForLaterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UsersController;
use Gloudemans\Shoppingcart\Facades\Cart;
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

Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/later/{product}', [CartController::class, 'later'])->name('cart.later');

Route::delete('/later/{product}', [SaveForLaterController::class, 'destroy'])->name('later.destroy');
Route::post('/later/cart/{product}', [SaveForLaterController::class, 'cart'])->name('later.cart');

Route::post('/coupon', [CouponsController::class, 'store'])->name('coupon.store');
Route::delete('/coupon', [CouponsController::class, 'destroy'])->name('coupon.destroy');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/guestCheckout', [CheckoutController::class, 'index'])->name('guestCheckout.index');

Route::get('/thankyou', [ConfirmationController::class, 'index'])->name('confirmation.index');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/search', [ShopController::class, 'search'])->name('search');
Route::get('/search-algolia', [ShopController::class, 'searchAlgolia'])->name('search-algolia');

Route::middleware('auth')->group(function() {
    Route::get('/my-profile', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/my-profile', [UsersController::class, 'update'])->name('users.update');
    Route::get('/my-orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
});

// helper routes
Route::get('/mailable/{order}', function ($order) {
    $order = \App\Models\Order::find($order);
    return new \App\Mail\OrderPlaced($order);
});

Route::get('empty', function () {
    Cart::destroy();
    Cart::instance('saveForLater')->destroy();
    Session::forget('coupon');
    return 'Cart is now empty';
});
