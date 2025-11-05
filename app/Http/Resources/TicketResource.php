<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->name,
            'sold' => $this->category->sold,
            'price' => $this->category->price,
            'event_id' => $this->event->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'purchase_date' => $this->created_at,
            'code' => $this->code,
            'event' => [
                'id' => $this->event->id,
                'title' => $this->event->name,
                'start_time' => $this->event->start_time,
                'end_time' => $this->event->end_time,
            ],
        ];
    }
}
