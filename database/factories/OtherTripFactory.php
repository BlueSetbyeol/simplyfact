<?php

namespace Database\Factories;

use App\Models\ExpensesClaim;
use App\Models\OtherTrip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OtherTrip>
 */
class OtherTripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expenses_claim_id' => ExpensesClaim::factory(),
            'expense_name' => $this->faker->sentence(5),
            'total_price' => 120,
            'reimbursed_price' => 120,
        ];
    }
}
