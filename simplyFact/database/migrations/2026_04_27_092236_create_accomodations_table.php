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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('expenses_claim_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('accommodation_type', 150);
            $table->integer('nb_of_night');
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('reimbursed_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
