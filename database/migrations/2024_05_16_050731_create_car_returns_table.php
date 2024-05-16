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
        Schema::create('car_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('car_rental_id')->constrained('car_rentals');
            $table->date('return_date');
            $table->integer('total_days');
            $table->unsignedBigInteger('total_cost');
            $table->foreignId('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_returns');
    }
};
