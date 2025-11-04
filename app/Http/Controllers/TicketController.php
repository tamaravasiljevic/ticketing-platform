<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketPurchaseRequest;
use App\Http\Resources\TicketResource;
use App\Models\TicketCategory;
use App\Services\TicketPurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function purchase(TicketPurchaseRequest $request, TicketPurchaseService $service)
    {
        $data = $request->input();
        $category = TicketCategory::findOrFail($data['category_id']);

        try {
            $response = $service->buy($request->user(), $category, $data['quantity']);
            return TicketResource::collection($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
