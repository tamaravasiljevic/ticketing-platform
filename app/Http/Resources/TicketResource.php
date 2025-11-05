<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;
/**
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     title="Event Resource",
 *     description="An event resource associated with a ticket",
 *     required={"id", "name", "start_time", "location"},
 *     @OA\Property(property="id", type="integer", example=10, description="The unique ID of the event"),
 *     @OA\Property(property="name", type="string", example="Concert XYZ", description="The name of the event"),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-11-10T20:00:00Z", description="The start time of the event in ISO8601 format"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-11-10T23:00:00Z", description="The end time of the event in ISO8601 format"),
 *     @OA\Property(property="location", type="string", example="Madison Square Garden", description="The location where the event is being held")
 * )
 * @OA\Schema(
 *     schema="Ticket Resource",
 *     type="object",
 *     title="Ticket Resource",
 *     description="A single ticket resource",
 *     required={"id", "category", "price", "sold"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID of the Ticket"),
 *     @OA\Property(property="category", type="string", example="Concert A", description="The category of the ticket"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99, description="Price of the ticket"),
 *     @OA\Property(property="sold", type="integer", example=3, description="Number of tickets sold"),
 *     @OA\Property(property="code", type="string", example="ABC123XYZ", description="Unique ticket code"),
 *     @OA\Property(property="event_id", type="integer", example=1, description="Event's id"),
 *     @OA\Property(property="user_id", type="integer", example=1, description="User's id"),
 *     @OA\Property(property="user_name", type="string", example="Jane Doe", description="User's name"),
 *     @OA\Property(property="purchase_date", type="date-time", example="2025-11-22T10:14:51.000000Z", description="Time of purchase"),
 *     @OA\Property(property="event", ref="#/components/schemas/Event", description="Details of the event associated with this ticket")
 * )
 */
class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = $this->category;
        $event = $this->category->event;
        return [
            'id' => $this->id,
            'category' => $category->name,
            'sold' => $category->sold,
            'price' => $category->price,
            'event_id' => $category->event_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'purchase_date' => $this->created_at,
            'code' => $this->code,
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'location' => $event->location
            ],
        ];
    }
}
