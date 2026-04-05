<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'price_per_month',
        'description',
        'image',
        'status',
        'max_tenants',
    ];

    public function amenities()
    {
        return $this->hasOne(Amenity::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
