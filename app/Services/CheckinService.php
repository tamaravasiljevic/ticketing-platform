<?php
namespace App\Services;

use App\Exceptions\TicketRedeemedException;
use App\Models\Checkin;
use App\Models\TicketCategory;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckinService
{
    public function processCheckin($user, Ticket $ticket) : ?Checkin
    {
        $lastCheckin = $ticket->checkins()->latest('checked_at')->first();

        if ($lastCheckin && $lastCheckin->type === Checkin::TYPE_IN) {
            throw new TicketRedeemedException(sprintf('Ticket with code %s already redeemed', $ticket->code));
        }

        $checkin = $ticket->checkins()->create([
            'type' => Checkin::TYPE_IN,
            'checked_at' => now(),
            'user_id' => $user->id ?? null
        ]);
        return $checkin;
    }
}
