@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <form method="GET" action="{{ route('apartments.index') }}" class="flex gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Search" class="border rounded px-2 py-1" />
            <input name="min_price" value="{{ request('min_price') }}" placeholder="Min price" class="border rounded px-2 py-1 w-24" />
            <input name="max_price" value="{{ request('max_price') }}" placeholder="Max price" class="border rounded px-2 py-1 w-24" />
            <button class="px-3 py-1 bg-blue-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($apartments as $apartment)
            <div class="bg-white rounded shadow p-4">
                <img src="{{ $apartment->image ?? 'https://via.placeholder.com/400x200' }}" alt="" class="w-full h-40 object-cover rounded" />
                <h3 class="mt-3 font-semibold text-lg">{{ $apartment->name }}</h3>
                <p class="text-sm text-gray-600">{{ $apartment->location }}</p>
                <p class="mt-2 font-bold">${{ number_format($apartment->price_per_month,2) }} / month</p>
                <div class="mt-3 flex justify-between items-center">
                    <a href="{{ route('apartments.show', $apartment) }}" class="text-sm text-blue-600">View</a>
                    @auth
                        <a href="{{ route('bookings.create', $apartment) }}" class="text-sm bg-green-600 text-white px-3 py-1 rounded">Book</a>
                    @else
                        <a href="/login" class="text-sm bg-green-600 text-white px-3 py-1 rounded">Login to Book</a>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $apartments->links() }}</div>
@endsection
