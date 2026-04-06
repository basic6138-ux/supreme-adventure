<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

// Lightweight health endpoint used by deploy healthchecks (used by Railway)
Route::get('/up', function () {
    return response('OK', 200);
});

// Debug endpoint to check database connection
Route::get('/api/debug/apartments', function () {
    try {
        $count = \App\Models\Apartment::count();
        $apartments = \App\Models\Apartment::limit(5)->get();
        return response()->json([
            'status' => 'ok',
            'total_count' => $count,
            'sample_apartments' => $apartments,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Show apartments on the home page for all environments
Route::get('/', [ApartmentController::class, 'index'])->name('home');
Route::get('/apartments', [ApartmentController::class, 'index'])->name('apartments.index');
Route::get('/apartments/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

// Simple dashboard for authenticated users (named `dashboard` expected by auth flows)
Route::middleware(['auth'])->get('/dashboard', function () {
    $apartmentsCount = \App\Models\Apartment::count();
    $bookingsCount = auth()->user()->bookings()->count();

    // recent items for dashboard
    $recentBookings = auth()->user()->bookings()->with('apartment')->latest()->take(5)->get();
    $recentApartments = \App\Models\Apartment::latest()->take(5)->get();

    return view('dashboard', compact('apartmentsCount','bookingsCount','recentBookings','recentApartments'));
})->name('dashboard');

// Quick guest login route for testing/demo — logs in the seeded student or creates a guest user.
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

// Auth routes (Laravel Breeze recommended to install)
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

// Include auth routes provided by Breeze or other auth scaffolding
if (file_exists(__DIR__.'/auth.php')) {
    require __DIR__.'/auth.php';
}
