<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('apartment')->latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function create(Apartment $apartment)
    {
        return view('bookings.create', compact('apartment'));
    }

    public function store(Request $request, Apartment $apartment)
    {
        $data = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Simple availability check
        $conflict = Booking::where('apartment_id', $apartment->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('check_in_date', [$data['check_in_date'], $data['check_out_date']])
                  ->orWhereBetween('check_out_date', [$data['check_in_date'], $data['check_out_date']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('check_in_date', '<=', $data['check_in_date'])
                         ->where('check_out_date', '>=', $data['check_out_date']);
                  });
            })->where('status', '!=', 'cancelled')->exists();

        if ($conflict) {
            return back()->with('error', 'The apartment is not available for the selected dates.');
        }

        $nights = now()->parse($data['check_in_date'])->diffInDays(now()->parse($data['check_out_date']));
        $total = $nights * $apartment->price_per_month / 30; // approximate daily price

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'apartment_id' => $apartment->id,
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'total_price' => round($total,2),
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        // create a pending payment record (demo)
        PaymentRecord::create([
            'booking_id' => $booking->id,
            'payment_method' => null,
            'amount' => $booking->total_price,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function cancel(Booking $booking)
    {
        $user = Auth::user();
        if (! ($user->is_admin || $booking->user_id == $user->id)) {
            abort(403);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}
