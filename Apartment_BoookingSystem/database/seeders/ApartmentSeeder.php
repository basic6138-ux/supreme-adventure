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
            ['name'=>'Maple Hall Flat A','location'=>'North Campus','price_per_month'=>500,'description'=>'Cozy 1BR near campus','image'=>null,'status'=>'available','max_tenants'=>2],
            ['name'=>'Oak Residence 3B','location'=>'West Campus','price_per_month'=>750,'description'=>'Spacious 2BR with parking','image'=>null,'status'=>'available','max_tenants'=>3],
            ['name'=>'Pine Studio','location'=>'South Campus','price_per_month'=>350,'description'=>'Studio ideal for students','image'=>null,'status'=>'available','max_tenants'=>1],
        ];

        foreach ($data as $d) {
            $apt = Apartment::create($d);
            Amenity::create(['apartment_id'=>$apt->id,'wifi'=>true,'parking'=>false,'air_conditioning'=>true,'furnished'=>true,'gym'=>false]);
        }
    }
}
