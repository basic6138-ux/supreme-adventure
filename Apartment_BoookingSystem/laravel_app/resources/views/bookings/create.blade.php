@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
        <h2 class="text-xl font-semibold mb-4">Book {{ $apartment->name }}</h2>

        <form method="POST" action="{{ route('bookings.store', $apartment) }}">
            @csrf
            <label class="block mb-2">Check-in</label>
            <input type="date" name="check_in_date" class="w-full border rounded px-2 py-1 mb-3" required>

            <label class="block mb-2">Check-out</label>
            <input type="date" name="check_out_date" class="w-full border rounded px-2 py-1 mb-3" required>

            <label class="block mb-2">Notes (optional)</label>
            <textarea name="notes" class="w-full border rounded px-2 py-1 mb-3"></textarea>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Confirm Booking</button>
        </form>
    </div>
@endsection
