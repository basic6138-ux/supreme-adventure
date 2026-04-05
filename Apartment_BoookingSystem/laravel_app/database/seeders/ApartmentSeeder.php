<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\Amenity;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Maple Hall Flat A',
                'location' => 'North Campus',
                'price_per_month' => 500,
                'description' => 'Cozy Apartment Living Room with Plants and Sofa',
                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688',
                'status' => 'available',
                'max_tenants' => 2,
            ],
            [
                'name' => 'Oak Residence 3B',
                'location' => 'West Campus',
                'price_per_month' => 750,
                'description' => 'Modern White Apartment Kitchen with Island',
                'image' => 'https://images.unsplash.com/photo-1600585152220-90363fe7e115',
                'status' => 'available',
                'max_tenants' => 3,
            ],
            [
                'name' => 'Pine Studio',
                'location' => 'South Campus',
                'price_per_month' => 350,
                'description' => 'Bright Modern Apartment Living Room with Yellow Chair',
                'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7',
                'status' => 'available',
                'max_tenants' => 1,
            ],
            [
                'name' => 'Retro Blue Kitchen Loft',
                'location' => 'East Campus',
                'price_per_month' => 620,
                'description' => 'Cozy Retro Blue Apartment Kitchen',
                'image' => 'https://pixabay.com/get/gaf17f303979145e8961407ed7dd0d07a58dd391fa57779af32825c31f85620a31853025d56a4897553454eb44908abd5_1920.jpg?longlived=',
                'status' => 'available',
                'max_tenants' => 2,
            ],
            [
                'name' => 'Minimalist Bedroom Suite',
                'location' => 'Central Campus',
                'price_per_month' => 700,
                'description' => 'Modern Minimalist Apartment Bedroom with Wall Art',
                'image' => 'https://pixabay.com/get/g2c77b493819a28fce7509faa07973e4d79ac854eef03a215be1b9ca22664c8246bb67610e11351545bc14f5ed080a938_1920.jpg?longlived=',
                'status' => 'available',
                'max_tenants' => 2,
            ],
            [
                'name' => 'Sunlit Living Room Apartment',
                'location' => 'North Annex',
                'price_per_month' => 680,
                'description' => 'Bright Modern Apartment Living Room',
                'image' => 'https://pixabay.com/get/g1f7f1f3c694f70c7da6fd39bd09c028e45f46f8040d9510cf8f907db8ec64b54a39c7a796d8d9d1dedcc34d221c89dfb_1920.jpg?longlived=',
                'status' => 'available',
                'max_tenants' => 2,
            ],
            [
                'name' => 'Stylish Living Space Flat',
                'location' => 'West Annex',
                'price_per_month' => 810,
                'description' => 'Stylish Modern Apartment Living Space',
                'image' => 'https://pixabay.com/get/g13df8e9ca293f10f59b17fcc19bb95d467f1bb910fe0f759872409dc492d62621526785ad3ca845199ad1efb6bd9071b_1920.jpg?longlived=',
                'status' => 'available',
                'max_tenants' => 3,
            ],
            [
                'name' => 'Sleek Kitchen Apartment',
                'location' => 'Tech Park Campus',
                'price_per_month' => 840,
                'description' => 'Sleek Modern Apartment Kitchen',
                'image' => 'https://pixabay.com/get/g54de7427935ee0a856bb5a66504cd3f14cc9c62f662d73461da64d1a033fa60317037c98ef42ba466c2ad1e09db62287_1920.jpg?longlived=',
                'status' => 'available',
                'max_tenants' => 3,
            ],
            [
                'name' => 'Studio Interior One',
                'location' => 'City Campus',
                'price_per_month' => 560,
                'description' => 'Stylish Studio Apartment Interior',
                'image' => 'https://images.pexels.com/photos/28148282/pexels-photo-28148282.jpeg?auto=compress&cs=tinysrgb&w=6123&fit=max',
                'status' => 'available',
                'max_tenants' => 1,
            ],
            [
                'name' => 'Balcony View Apartment',
                'location' => 'Downtown Campus',
                'price_per_month' => 920,
                'description' => 'Modern Apartment Building Exterior with Balconies',
                'image' => 'https://images.pexels.com/photos/19991829/pexels-photo-19991829.jpeg?auto=compress&cs=tinysrgb&w=4082&fit=max',
                'status' => 'available',
                'max_tenants' => 4,
            ],
        ];

        foreach ($data as $d) {
            $apt = Apartment::updateOrCreate(['name' => $d['name']], $d);
            Amenity::updateOrCreate(
                ['apartment_id' => $apt->id],
                ['wifi'=>true,'parking'=>false,'air_conditioning'=>true,'furnished'=>true,'gym'=>false]
            );
        }
    }
}
