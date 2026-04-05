<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apartment Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-semibold">Apartment Booking</a>
            <nav class="space-x-4">
                <a href="{{ route('apartments.index') }}">Browse</a>
                @auth
                    <a href="{{ route('bookings.index') }}">My Bookings</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">@csrf<button class="ml-2 text-sm">Logout</button></form>
                @else
                    <a href="/login">Login</a>
                    <a href="/register">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-sm text-gray-600">&copy; {{ date('Y') }} University Apartment Booking</div>
    </footer>
</body>
</html>
