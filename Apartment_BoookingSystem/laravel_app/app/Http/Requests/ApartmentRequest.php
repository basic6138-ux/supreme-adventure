<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApartmentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price_per_month' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,booked,unavailable',
            'max_tenants' => 'required|integer|min:1',
        ];
    }
}
