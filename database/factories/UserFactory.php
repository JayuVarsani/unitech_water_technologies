<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\user>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            //            'name',
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->streetAddress(),
            'city' => fake()->city(),
            'district' => fake()->city(),
            'state' => fake()->country(),
            'pincode' => fake()->postcode(),
            'phone_number' => fake()->phoneNumber(),
            'alternate_phone_number' => fake()->phoneNumber(),
            'imei' => fake()->imei(),
            'email' => fake()->safeEmail(),
            'join_at' => fake()->date(),
        ];
    }
}
