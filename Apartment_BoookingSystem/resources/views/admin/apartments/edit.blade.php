@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Edit Apartment</h2>

    <form method="POST" action="{{ route('admin.apartments.update', $apartment) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Name</label>
                <input name="name" value="{{ $apartment->name }}" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Location</label>
                <input name="location" value="{{ $apartment->location }}" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Price per month</label>
                <input name="price_per_month" type="number" step="0.01" value="{{ $apartment->price_per_month }}" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Max tenants</label>
                <input name="max_tenants" type="number" value="{{ $apartment->max_tenants }}" class="w-full border rounded px-2 py-1" required>
            </div>
        </div>
        <div class="mt-4">
            <label class="block">Description</label>
            <textarea name="description" class="w-full border rounded px-2 py-1">{{ $apartment->description }}</textarea>
        </div>
        <div class="mt-4">
            <label class="block">Status</label>
            <select name="status" class="w-full border rounded px-2 py-1">
                <option value="available" {{ $apartment->status==='available' ? 'selected' : ''}}>Available</option>
                <option value="booked" {{ $apartment->status==='booked' ? 'selected' : ''}}>Booked</option>
                <option value="unavailable" {{ $apartment->status==='unavailable' ? 'selected' : ''}}>Unavailable</option>
            </select>
        </div>

        <div class="mt-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
@endsection
