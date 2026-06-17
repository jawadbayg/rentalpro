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
use App\Http\Controllers\FleetProviderController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use App\Models\Booking;

Route::get('/', [LandingPageController::class, 'getFleet']);
Route::get('/vehicle/{id}', [LandingPageController::class, 'show'])->name('vehicle.show');
  
Auth::routes();

Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])
    ->middleware(['auth', 'role:Admin'])
    ->name('admin.dashboard');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::resource('fleet-providers', FleetProviderController::class)->except(['show']);
});

  
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);

    Route::get('/profile/settings/{id}', [UserController::class, 'profilePage'])->name('profile.settings');

    Route::post('/profile/upload/{id}', [UserController::class, 'uploadProfilePicture'])->name('profile.upload');
    Route::put('/profile/password/{id}', [UserController::class, 'updatePassword'])->name('profile.password');

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

    Route::get('checkout/{booking_id}', [BookingController::class, 'showCheckout'])->name('checkout');
    Route::post('checkout/{booking_id}', [BookingController::class, 'processPayment'])->name('checkout.process');
    Route::get('checkout/{booking_id}/success', [BookingController::class, 'paymentSuccessPage'])->name('checkout.success');
    Route::post('/payment-success/{booking_id}', [BookingController::class, 'paymentSuccessChanges'])->name('payment.success');

});


Route::get('about-us',[UserController::Class,'about_us_index'])->name('about.us.index');

Route::prefix('fleet')->name('fleet.')->group(function() {
    Route::get('/', [FleetController::class, 'index'])->name('index');
    Route::get('/create', [FleetController::class, 'create'])->name('create');
    Route::post('/', [FleetController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FleetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FleetController::class, 'update'])->name('update');
    Route::post('/delete/{id}', [FleetController::class, 'destroy'])->name('delete');
});

