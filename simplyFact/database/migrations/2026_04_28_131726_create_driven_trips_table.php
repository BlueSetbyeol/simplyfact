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
        Schema::create('driven_trips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('expenses_claim_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignUuid('vehicle_id')
                ->constrained();
            $table->string('starting_city', 150);
            $table->integer('strating_zip_code');
            $table->string('ending_city', 150);
            $table->integer('ending_zip_code');
            $table->string('trip_type', 255)->nullable;
            $table->integer('total_distance');
            $table->float('total_price');
            $table->integer('total_distance_given')->nullable;
            $table->float('total_price_given')->nullable;
            $table->float('reimbursed_price');
            $table->string('description', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driven_trips');
    }
};
