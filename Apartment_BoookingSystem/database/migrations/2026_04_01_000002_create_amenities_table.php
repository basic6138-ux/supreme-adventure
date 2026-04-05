<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->boolean('wifi')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('air_conditioning')->default(false);
            $table->boolean('furnished')->default(false);
            $table->boolean('gym')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('amenities');
    }
};
