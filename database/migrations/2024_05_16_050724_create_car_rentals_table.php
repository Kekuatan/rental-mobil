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
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('created_by');
            $table->foreignUuid('car_id')->constrained('cars');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['ready','ordered','reject', 'ongoing', 'completed']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
