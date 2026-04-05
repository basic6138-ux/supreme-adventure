@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-sm text-gray-600 mt-1">Quick overview of your account and recent activity.</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('apartments.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Browse Apartments</a>
                <a href="{{ route('bookings.index') }}" class="inline-block px-4 py-2 bg-gray-100 text-gray-800 rounded border">My Bookings</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium">Apartments</h2>
            <p class="text-3xl font-bold mt-4">{{ $apartmentsCount ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-2">Total apartments listed.</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium">Your Bookings</h2>
            <p class="text-3xl font-bold mt-4">{{ $bookingsCount ?? 0 }}</p>
            <p class="text-sm text-gray-500 mt-2">Active bookings on your account.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium mb-3">Getting started</h3>
        <ul class="list-disc pl-5 text-sm text-gray-700">
            <li>Use <strong>Browse Apartments</strong> to find available units.</li>
            <li>Click <strong>Book</strong> on an apartment to create a booking.</li>
            <li>Visit <strong>My Bookings</strong> to manage or cancel bookings.</li>
        </ul>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium mb-3">Recent Bookings</h3>
            @if(isset($recentBookings) && $recentBookings->isNotEmpty())
                <ul class="space-y-3">
                    @foreach($recentBookings as $b)
                        <li class="flex justify-between items-start">
                            <div>
                                <div class="font-medium">{{ $b->apartment->name ?? 'Apartment' }}</div>
                                <div class="text-sm text-gray-500">From {{ \Carbon\Carbon::parse($b->check_in_date)->toFormattedDateString() }} to {{ \Carbon\Carbon::parse($b->check_out_date)->toFormattedDateString() }}</div>
                            </div>
                            <div class="text-sm px-2 py-1 rounded {{ $b->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($b->status) }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">You have no recent bookings.</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium mb-3">New Apartments</h3>
            @if(isset($recentApartments) && $recentApartments->isNotEmpty())
                <ul class="space-y-3">
                    @foreach($recentApartments as $a)
                        <li>
                            <a href="{{ route('apartments.show', $a) }}" class="block hover:underline">
                                <div class="font-medium">{{ $a->name }}</div>
                                <div class="text-sm text-gray-500">{{ $a->location }} — ${{ number_format($a->price_per_month,2) }}/mo</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No apartments listed yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
