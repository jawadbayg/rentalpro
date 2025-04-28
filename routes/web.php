<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VerificationController;

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
});


Route::prefix('fleet')->name('fleet.')->group(function() {
    Route::get('/', [FleetController::class, 'index'])->name('index');
    Route::get('/create', [FleetController::class, 'create'])->name('create');
    Route::post('/', [FleetController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FleetController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FleetController::class, 'update'])->name('update');
    // Route::get('/{id}', [FleetController::class, 'show'])->name('show');
});

