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
        return DB::transaction(function () use ($user, $category, $quantity) {
            // lock row - disable overselling
            $category = TicketCategory::where('id', $category->id)
                ->lockForUpdate()
                ->first();

            if (!$category->is_active || ($category->sold + $quantity > $category->quota)) {
                throw new Exception('Category sold.');
            }

            $alreadyBought = Ticket::where('user_id', $user->id)
                ->where('ticket_category_id', $category->id)
                ->count();

            if ($alreadyBought + $quantity > $category->max_per_user) {
                throw new Exception('Maximum number of tickets per user exceeded.');
            }

            // create tickets
            for ($i = 0; $i < $quantity; $i++) {
                Ticket::create([
                    'user_id' => $user->id,
                    'ticket_category_id' => $category->id,
                ]);
            }

            // update sale
            $category->increment('sold', $quantity);

            if ($category->sold >= $category->quota) {
                $category->update(['is_active' => false]);
            }

            return true;
        });
    }
}
