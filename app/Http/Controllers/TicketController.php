<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketPurchaseRequest;
use App\Services\TicketPurchaseService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function purchase(TicketPurchaseRequest $request, TicketPurchaseService $service)
    {
        //remove this
        $data = $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $category = TicketCategory::findOrFail($data['category_id']);

        try {
            $service->buy($request->user(), $category, $data['quantity']);
            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
