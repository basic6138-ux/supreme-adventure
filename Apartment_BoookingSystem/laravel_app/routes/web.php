<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

// Lightweight health endpoint used by deploy healthchecks (used by Railway)
Route::get('/up', function () {
    return response('OK', 200);
});

// Home page - show apartments
Route::get('/', [ApartmentController::class, 'index'])->name('home');
Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

// Dashboard
Route::middleware(['auth'])->get('/dashboard', function () {
    $apartmentsCount = \App\Models\Apartment::count();
    $bookingsCount = auth()->user()->bookings()->count();
    $recentBookings = auth()->user()->bookings()->with('apartment')->latest()->take(5)->get();
    $recentApartments = \App\Models\Apartment::latest()->take(5)->get();
    return view('dashboard', compact('apartmentsCount','bookingsCount','recentBookings','recentApartments'));
})->name('dashboard');

// Guest login
Route::get('/guest-login', function () {
    $email = 'student@example.com';
    $user = \App\Models\User::where('email', $email)->first();
    if (! $user) {
        $user = \App\Models\User::create([
            'name' => 'Guest Student',
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->route('dashboard');
});

// Auth routes
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/apartments/{apartment}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/apartments/{apartment}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('apartments', AdminController::class)->only(['index','create','store','edit','update','destroy']);
});

// Include auth routes - re-enabled
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
