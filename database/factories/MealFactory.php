<?php

namespace Database\Factories;

use App\Models\ExpensesClaim;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meal>
 */
class MealFactory extends Factory
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
            'number_of_meal' => 6,
            'total_price' => 140,
            'reimbursed_price' => 140,
        ];
    }
}
