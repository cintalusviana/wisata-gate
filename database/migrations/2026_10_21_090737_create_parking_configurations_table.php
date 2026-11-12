<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type', 50)->unique();
            $table->unsignedInteger('total_slots')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_configurations');
    }
};
