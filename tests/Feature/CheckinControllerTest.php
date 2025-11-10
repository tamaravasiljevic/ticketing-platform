<?php

namespace Tests\Feature;

use App\Http\Controllers\CheckinController;
use App\Models\Checkin;
use App\Models\Ticket;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckinControllerTest extends TestCase
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
     * @see CheckinController::checkIn()
     */
    public function test_checkin_without_authenticated_user()
    {
        $response = $this->postJson('/api/check-in', []);
        $data = json_decode($response->getContent(), true);
        $response->assertStatus(401);
        self::assertEquals('Unauthenticated.', $data['message']);
    }

    /**
     * @see CheckinController::checkIn()
     */
    public function test_checkin_without_required_fields()
    {
        $response = $this->actingAs($this->user)->postJson('/api/check-in', []);

        // Assert validation errors
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }

    public function test_checkin_with_invalid_code()
    {
        $response = $this->actingAs($this->user)->postJson('/api/check-in',
            [
                'code' => '871ACA9102',
            ]
        );
        // Assert errors
        $response->assertStatus(422);
        $response->assertJsonFragment(
            [
                'message' => 'The selected code is invalid.',
            ]
        );
    }

    public function test_checkin_with_valid_code_success ()
    {
        $ticket = Ticket::factory()->create();
        $response = $this->actingAs($this->user)->postJson('/api/check-in',
            [
                'code' => $ticket->code
            ]
        );
        $response->assertStatus(200);
        $response->assertJsonFragment(
            [
                'message' => 'Check-in successful',
                'code' => $ticket->code
            ]
        );

        $checkin = Checkin::firstWhere(['ticket_id' => $ticket->id]);
        self::assertNotNull($checkin);
        self::assertEquals(Checkin::TYPE_IN, $checkin->type);
        self::assertEquals($checkin->user_id, $this->user->id);
    }

    public function test_check_in_ticket_already_redeemed()
    {
        $ticket = Ticket::factory()->create();
        $checkin = Checkin::create([
            'ticket_id' => $ticket->id,
            'type' => Checkin::TYPE_IN,
            'checked_at' => Carbon::now(),
            'user_id' => $this->user->id,
        ]);
        $response = $this->actingAs($this->user)->postJson('/api/check-in',
            [
                'code' => $ticket->code
            ]
        );

        $response->assertStatus(400);
        $response->assertJsonFragment(
            [
                'message' => sprintf('Ticket with code %s already redeemed', $ticket->code)
            ]
        );
    }
}
