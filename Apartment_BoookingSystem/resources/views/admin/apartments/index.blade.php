@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Manage Apartments</h2>
        <a href="{{ route('admin.apartments.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">New</a>
    </div>

    <div class="space-y-4">
        @foreach($apartments as $a)
            <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                <div>
                    <div class="font-semibold">{{ $a->name }}</div>
                    <div class="text-sm text-gray-600">{{ $a->location }}</div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.apartments.edit', $a) }}" class="text-sm px-2 py-1 border rounded">Edit</a>
                    <form action="{{ route('admin.apartments.destroy', $a) }}" method="POST">@csrf @method('DELETE')<button class="text-sm px-2 py-1 border rounded">Delete</button></form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $apartments->links() }}</div>
@endsection
