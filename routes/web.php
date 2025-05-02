<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use App\Models\Booking;

Route::get('/', [LandingPageController::class, 'getFleet']);
Route::get('/vehicle/{id}', [LandingPageController::class, 'show'])->name('vehicle.show');
  
Auth::routes();
  
Route::get('/home', [HomeController::class, 'index'])->name('home');

  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::get('/profile/settings/{id}', [UserController::class, 'profilePage'])->name('profile.settings');

    Route::post('/profile/upload/{id}', [UserController::class, 'uploadProfilePicture'])->name('profile.upload');

    // user verification page and store
    Route::get('/user-verification', [UserController::class, 'createUserVerification'])->name('user.validation');
    Route::post('/user-validation/store', [UserController::class, 'userValidationStore'])->name('user_validation.store');

    // admin side user verification  
    Route::get('/verification-requests', [VerificationController::class, 'index'])->name('verification_requests.index');
    Route::post('/user-validation/approve', [VerificationController::class, 'approve'])->name('user_validation.approve');

    // booking 
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'customer_index'])->name('customer.bookings.index');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{id}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::get('/invoices', [BookingController::class, 'invoiceIndex'])->name('invoices.index');
    Route::get('/invoices/{id}/download', [BookingController::class, 'invoiceDownload'])->name('invoices.download');

    Route::post('/check-date', [BookingController::class, 'checkDate'])->name('check.date');

    Route::get('/payments', [BookingController::class, 'paymentHistoryIndex'])->name('payments.index');

});




Route::prefix('fleet')->name('fleet.')->group(function() {
    Route::get('/', [FleetController::class, 'index'])->name('index');
    Route::get('/create', [FleetController::class, 'create'])->name('create');
    Route::post('/', [FleetController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FleetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FleetController::class, 'update'])->name('update');
    // Route::get('/{id}', [FleetController::class, 'show'])->name('show');
});

// Route::get('/checkout', function (Request $request) {

//     $user = auth()->user();

//     $stripePriceId = 'price_1RKCcZ4gfmenhky10ANUU7Y1';
 
//     $quantity = 1;
 
//     return $user->checkout([$stripePriceId => $quantity], [
//         'success_url' => route('checkout-success'),
//         'cancel_url' => route('checkout-cancel'),
//     ]);
// })->name('checkout');

Route::get('checkout/{booking_id}', function ($booking_id) {
    $user = Auth::user();
    
    $booking = Booking::where('id', $booking_id)
                      ->where('customer_id', $user->id)
                      ->where('is_cancelled',null)
                      ->firstOrFail();


    $amount = $booking->total_price * 100;

    $intent = $user->pay($amount);

    return view('stripe.checkout', compact('intent', 'booking'));
})->name('checkout');
 
Route::get('/checkout/success',  function(){
    return 'Success Page';
})->name('checkout-success');
Route::get('/checkout/cancel', function(){
    return 'Cancel Page';
})->name('checkout-cancel');

Route::post('/payment-success/{booking_id}', [BookingController::class, 'paymentSuccessChanges'])->name('payment.success');
