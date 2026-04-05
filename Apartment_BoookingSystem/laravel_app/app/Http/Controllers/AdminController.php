<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Amenity;
use App\Models\Booking;
use App\Http\Requests\ApartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function isAdmin()
    {
        return Auth::user() && Auth::user()->is_admin;
    }

    public function dashboard()
    {
        if (! $this->isAdmin()) abort(403);

        $apartmentsCount = Apartment::count();
        $bookingsCount = Booking::count();
        $recentBookings = Booking::with('user','apartment')->latest()->limit(10)->get();

        return view('admin.dashboard', compact('apartmentsCount','bookingsCount','recentBookings'));
    }

    // Basic CRUD for apartments
    public function index()
    {
        if (! $this->isAdmin()) abort(403);
        $apartments = Apartment::latest()->paginate(15);
        return view('admin.apartments.index', compact('apartments'));
    }

    public function create()
    {
        if (! $this->isAdmin()) abort(403);
        return view('admin.apartments.create');
    }

    public function store(ApartmentRequest $request)
    {
        if (! $this->isAdmin()) abort(403);

        $data = $request->validated();

        $apartment = Apartment::create($data);

        Amenity::create(['apartment_id' => $apartment->id]);

        return redirect()->route('admin.apartments.index')->with('success','Apartment created.');
    }

    public function edit(Apartment $apartment)
    {
        if (! $this->isAdmin()) abort(403);
        return view('admin.apartments.edit', compact('apartment'));
    }

    public function update(ApartmentRequest $request, Apartment $apartment)
    {
        if (! $this->isAdmin()) abort(403);

        $data = $request->validated();

        $apartment->update($data);

        return redirect()->route('admin.apartments.index')->with('success','Apartment updated.');
    }

    public function destroy(Apartment $apartment)
    {
        if (! $this->isAdmin()) abort(403);
        $apartment->delete();
        return back()->with('success','Apartment deleted.');
    }
}
