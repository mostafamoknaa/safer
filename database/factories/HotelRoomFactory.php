<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HotelRoom>
 */
class HotelRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => \App\Models\Hotel::factory(),
            'name' => 'Room ' . $this->faker->numberBetween(100, 999),
            'type' => 'standard',
            'price_per_night' => 100.00,
            'max_people' => 2,
            'beds_count' => 1,
            'bathrooms_count' => 1,
            'rooms_count' => 1,
            'is_active' => true,
        ];
    }
}
