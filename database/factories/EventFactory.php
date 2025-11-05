<?php
namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;


class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4), // Generates a random title with 4 words
            'start_time' => $this->faker->dateTimeBetween('now', '+1 month'), // Random start time between now and next month
            'end_time' => $this->faker->dateTimeBetween('+1 month', '+2 months'), // Random end time after start time
            'location' => $this->faker->address, // Generates a random address
        ];
    }
}
