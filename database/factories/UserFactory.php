<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'address_street' => fake()->streetAddress(),
            'address_zipcode' => fake()->numerify('#####'),
            'address_city' => fake()->city(),
            'address_country' => fake()->country(),
            'email_address' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->numerify('0#########'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    // public function withTwoFactor(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'two_factor_secret' => encrypt('secret'),
    //         'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
    //         'two_factor_confirmed_at' => now(),
    //     ]);
    // }
}
