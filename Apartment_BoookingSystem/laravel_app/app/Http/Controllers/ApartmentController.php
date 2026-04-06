<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Start with a simple query
            $query = Apartment::query();
            
            // Only apply status filter if apartments exist
            if (Apartment::count() > 0) {
                $query->where('status', 'available');
            }

            // Apply search filters
            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->q.'%')
                      ->orWhere('location', 'like', '%'.$request->q.'%');
                });
            }

            if ($request->filled('min_price')) {
                $query->where('price_per_month', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price_per_month', '<=', $request->max_price);
            }

            $apartments = $query->paginate(9)->withQueryString();
            return view('apartments.index', compact('apartments'));
            
        } catch (\Throwable $e) {
            \Log::error('ApartmentController@index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            // Return a safe fallback view
            return view('apartments.index', ['apartments' => collect([])->paginate(0)])
                ->with('error', 'Error loading apartments: ' . $e->getMessage());
        }
    }

    public function show(Apartment $apartment)
    {
        return view('apartments.show', compact('apartment'));
    }
}
