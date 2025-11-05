<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketCategory>
 */
class TicketCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory()->create()->id,
            'name' =>  $this->faker->sentence(2), // Generates a random name with 2 words
            'price' => $this->faker->randomDigit(),
            'quota' => 10,
            'sold' => 0,
            'is_active' => 1
        ];
    }
}
