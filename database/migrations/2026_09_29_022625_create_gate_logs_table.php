<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gate_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->string('purpose');
            $table->dateTime('gate_in');
            $table->dateTime('gate_out')->nullable();

            $table->enum('status', ['Di Dalam', 'Sudah Keluar'])
                ->default('Di Dalam');

            $table->foreignId('officer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gate_logs');
    }
};