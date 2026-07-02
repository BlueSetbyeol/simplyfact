<?php

namespace Database\Factories;

use App\Models\ExpensesClaim;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpensesClaim>
 */
class ExpensesClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'committee_name' => $this->faker->company(),
            'action_name' => $this->faker->sentence(5),
            'action_dates' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}
