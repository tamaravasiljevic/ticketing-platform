<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket; // Import related models
use App\Models\TicketCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => 'test1234',
        ]);
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_without_required_fields()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase', []);

        // Assert validation errors
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);
        $response->assertJsonValidationErrors(['quantity']);
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_category_not_found ()
    {
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase',
            [
                'category_id' => 999,
                'quantity' => 1
            ]
        );

        // Assert validation errors
        $response->assertStatus(422);
        $response->assertJsonFragment(
            [
                'message' => 'The selected category id is invalid.'
            ]
        );
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_max_number_tickets_per_user_exceeded()
    {
        $event = Event::factory()->create(
            [
                'max_tickets_per_customer' => 1
            ]
        );
        $ticketCategory = TicketCategory::factory()->create(['event_id' => $event->id]);
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase',
            [
                'category_id' => $ticketCategory->id,
                'quantity' => 5
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'Maximum number of tickets per user exceeded.'
        ]);
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_category_inactive()
    {
        $ticketCategory = TicketCategory::factory()->create(['is_active' => 0]);
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase',
            [
                'category_id' => $ticketCategory->id,
                'quantity' => 1
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'Category sold out.'
        ]);
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_category_sold_out()
    {
        $ticketCategory = TicketCategory::factory()->create(['quota' => 100, 'sold' => 100]);
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase',
            [
                'category_id' => $ticketCategory->id,
                'quantity' => 1
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'Category sold out.'
        ]);
    }

    /**
     * @see TicketController::purchase()
     */
    public function test_ticket_purchase_success()
    {
        $ticketCategory = TicketCategory::factory()->create(['quota' => 100, 'price' => 99.99]);
        $response = $this->actingAs($this->user)->postJson('/api/tickets/purchase',
            [
                'category_id' => $ticketCategory->id,
                'quantity' => 3
            ]
        );

        $response->assertStatus(200);
        $tickets = Ticket::where('ticket_category_id', $ticketCategory->id)->where('user_id', $this->user->id)->get();
        self::assertCount(3, $tickets);
        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData['data'] as $data) {
            self::assertEquals($data['category'], $ticketCategory->name);
            self::assertEquals(3, $data['sold']);
            self::assertEquals(99.99, $data['price']);
            self::assertEquals($ticketCategory->event_id, $data['event_id']);
            self::assertEquals($this->user->id, $data['user_id']);
            self::assertEquals($this->user->name, $data['user_name']);
            self::assertNotEmpty($data['code']);
        }
    }
}
