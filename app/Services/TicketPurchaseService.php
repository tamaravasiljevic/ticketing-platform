<?php
namespace App\Services;

use App\Models\TicketCategory;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Exception;

class TicketPurchaseService
{
    public function buy($user, TicketCategory $category, $quantity)
    {
        $response = [];
        return DB::transaction(function () use ($user, $category, $quantity, $response) {
            // lock row - disable overselling
            $category = TicketCategory::where('id', $category->id)
                ->lockForUpdate()
                ->first();

            if (!$category->is_active || ($category->sold + $quantity > $category->quota)) {
                throw new Exception('Category sold out.', 422);
            }

            $alreadyBought = Ticket::where('user_id', $user->id)
                ->where('ticket_category_id', $category->id)
                ->count();

            if ($alreadyBought + $quantity > $category->event->max_tickets_per_customer) {
                throw new Exception('Maximum number of tickets per user exceeded.', 422);
            }

            // create tickets
            for ($i = 0; $i < $quantity; $i++) {
                $response[] = Ticket::create([
                    'user_id' => $user->id,
                    'original_user_id' => $user->id,
                    'ticket_category_id' => $category->id,
                ]);
            }

            // update sale
            $category->increment('sold', $quantity);

            if ($category->sold >= $category->quota) {
                $category->update(['is_active' => false]);
            }

            return $response;
        });
    }
}
