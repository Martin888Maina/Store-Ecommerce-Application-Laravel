<?php
//import the product controller file
use App\Http\Controllers\ProductController;
//import the cart controller file
use App\Http\Controllers\CartController;
//import the order controller file
use App\Http\Controllers\OrderController;
//import the auth controller file
use App\Http\Controllers\AuthController;
//import the authentication controller file
use Illuminate\Support\Facades\Route;
// //import the payment controller file
// use App\Http\Controllers\PaymentController;


use Illuminate\Http\Request;

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
//Product Routes
//landing page and displays all the products on the landing page
Route::get('/', [ProductController::class, 'index'])->name('products.index');
//displays the upload form that is used to upload product data to the database
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
//send data the products database
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
//displays the add to cart page that display a single selected product
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes
// Viewing a cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Adding a product to a cart
Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
// Removing a product from a cart
Route::post('/cart/remove/{cartItem}', [CartController::class, 'removeFromCart'])->name('cart.remove');
// Viewing a cart summary (final display page)
// protected route
Route::get('/cart/display', [CartController::class, 'display'])
    ->name('cart.display')
    ->middleware('auth');

// Checkout Routes
Route::middleware('auth')->group(function () {
    //route that leads to the checkout page
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    //route that allows user to place an order
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
});

// Register Routes
//displays the register form
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
//submitting the register form
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Login Routes
//displays the login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
//submitting the login form
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
//logging out
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
//show the forgot password form
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
//sends the reset link via email
Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
//show reset password form
Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
//handles password reset functionality
Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');


//Email Verification Routes
//shows a notice to the user to verify their email
Route::get('/email/verify', function () {
    return view('auth.notice'); // Replace with your verification view
})->name('verification.notice');
//handles the actual verification process
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//throttle limits the number of times a route can be accessed
//6 requests in 1 minute



// //quickteller payment routes
// // This route is used to display the payment page to the user.
// Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
// // This route handles the payment processing when the user submits the payment form.
// Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
// // This route handles the payment gateway callback (also known as the webhook or IPN - Instant Payment Notification
// Route::get('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');