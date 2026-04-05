@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">My Bookings</h2>

    <div class="space-y-4">
        @forelse($bookings as $booking)
            <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                <div>
                    <div class="font-semibold">{{ $booking->apartment->name }}</div>
                    <div class="text-sm text-gray-600">{{ $booking->check_in_date }} → {{ $booking->check_out_date }}</div>
                    <div class="text-sm">Status: {{ ucfirst($booking->status) }}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold">₱{{ number_format($booking->total_price,2) }}</div>
                    @if($booking->status !== 'cancelled')
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                            @csrf
                            <button class="mt-2 text-sm bg-red-500 text-white px-3 py-1 rounded">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p>No bookings yet.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $bookings->links() }}</div>
@endsection
