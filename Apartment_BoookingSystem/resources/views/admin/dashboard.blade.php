@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">Apartments: <strong>{{ $apartmentsCount }}</strong></div>
        <div class="bg-white p-4 rounded shadow">Bookings: <strong>{{ $bookingsCount }}</strong></div>
        <div class="bg-white p-4 rounded shadow">Recent bookings</div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold">Recent bookings</h3>
        <ul class="mt-2 space-y-2">
            @foreach($recentBookings as $b)
                <li class="bg-white p-3 rounded shadow">{{ $b->user->name }} booked {{ $b->apartment->name }} ({{ $b->status }})</li>
            @endforeach
        </ul>
    </div>
@endsection
