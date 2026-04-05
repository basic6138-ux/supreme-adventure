@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <img src="{{ $apartment->image ?? 'https://via.placeholder.com/800x400' }}" alt="" class="w-full h-80 object-cover rounded" />
                <h1 class="mt-4 text-2xl font-semibold">{{ $apartment->name }}</h1>
                <p class="text-gray-600">{{ $apartment->location }}</p>
                <p class="mt-4">{{ $apartment->description }}</p>
            </div>
            <aside class="p-4 bg-gray-50 rounded">
                <p class="font-bold text-lg">${{ number_format($apartment->price_per_month,2) }} / month</p>
                <p class="text-sm text-gray-600">Max tenants: {{ $apartment->max_tenants }}</p>
                <div class="mt-4">
                    @auth
                        <a href="{{ route('bookings.create', $apartment) }}" class="block bg-green-600 text-white text-center py-2 rounded">Book Now</a>
                    @else
                        <a href="/login" class="block bg-green-600 text-white text-center py-2 rounded">Login to Book</a>
                    @endauth
                </div>
            </aside>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold">Amenities</h3>
            @if($apartment->amenities)
                <ul class="mt-2 text-sm text-gray-700">
                    <li>WiFi: {{ $apartment->amenities->wifi ? 'Yes' : 'No' }}</li>
                    <li>Parking: {{ $apartment->amenities->parking ? 'Yes' : 'No' }}</li>
                    <li>AC: {{ $apartment->amenities->air_conditioning ? 'Yes' : 'No' }}</li>
                    <li>Furnished: {{ $apartment->amenities->furnished ? 'Yes' : 'No' }}</li>
                    <li>Gym: {{ $apartment->amenities->gym ? 'Yes' : 'No' }}</li>
                </ul>
            @else
                <p class="text-sm text-gray-500">No amenities listed.</p>
            @endif
        </div>
    </div>
@endsection
