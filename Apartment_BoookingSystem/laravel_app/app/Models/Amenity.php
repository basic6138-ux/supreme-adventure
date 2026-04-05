<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'wifi',
        'parking',
        'air_conditioning',
        'furnished',
        'gym',
    ];

    protected $casts = [
        'wifi' => 'boolean',
        'parking' => 'boolean',
        'air_conditioning' => 'boolean',
        'furnished' => 'boolean',
        'gym' => 'boolean',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
