@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Create Apartment</h2>

    <form method="POST" action="{{ route('admin.apartments.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Name</label>
                <input name="name" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Location</label>
                <input name="location" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Price per month</label>
                <input name="price_per_month" type="number" step="0.01" class="w-full border rounded px-2 py-1" required>
            </div>
            <div>
                <label class="block">Max tenants</label>
                <input name="max_tenants" type="number" class="w-full border rounded px-2 py-1" required>
            </div>
        </div>
        <div class="mt-4">
            <label class="block">Description</label>
            <textarea name="description" class="w-full border rounded px-2 py-1"></textarea>
        </div>
        <div class="mt-4">
            <label class="block">Status</label>
            <select name="status" class="w-full border rounded px-2 py-1">
                <option value="available">Available</option>
                <option value="booked">Booked</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        <div class="mt-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
        </div>
    </form>
@endsection
