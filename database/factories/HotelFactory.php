<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => $this->faker->company,
            'name_en' => $this->faker->company,
            'address_ar' => $this->faker->address,
            'address_en' => $this->faker->address,
            'province_id' => \App\Models\Province::factory(),
            'is_active' => true,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
