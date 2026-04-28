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
        Schema::create('training_expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('expenses_claim_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('nb_days_of_training');
            $table->float('total_price');
            $table->float('reimbursed_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_expenses');
    }
};
