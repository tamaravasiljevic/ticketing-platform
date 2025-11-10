<?php

namespace Database\Factories;

use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'original_user_id' => User::factory()->create()->id,
            'ticket_category_id' => TicketCategory::factory()->create()->id,
        ];
    }
}
