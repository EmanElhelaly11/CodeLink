<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'imageUrl' => $this->faker->imageUrl(),
            'track' => $this->faker->randomElement(['track1', 'track2', 'track3']),
            'bio' => $this->faker->paragraph,
            'role' => $this->faker->randomElement(['admin', 'sub-admin', 'reviewer', 'user']),
            'code' => $this->faker->randomNumber(),
            'code_expired_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
