<?php

namespace App\Http\Controllers;

use App\Exceptions\TicketCategoryNotFoundException;
use App\Http\Requests\TicketPurchaseRequest;
use App\Http\Resources\TicketResource;
use App\Models\TicketCategory;
use App\Services\TicketPurchaseService;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *     title="Ticketing API",
 *     version="1.0.0",
 *     description="API for managing event tickets"
 * )
 */
class TicketController extends Controller
{
    /**
     * Purchase a ticket.
     *
     * @OA\Post(
     *     path="/api/tickets/purchase",
     *     summary="Purchase a ticket",
     *     operationId="purchaseTicket",
     *     tags={"Tickets"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="event_id", type="integer", example=5, description="ID of the event"),
     *             @OA\Property(property="quantity", type="integer", example=2, description="Number of tickets to purchase")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket successfully purchased",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/Ticket Resource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input data",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error")
     *         )
     *     )
     * )
     */
    public function purchase(TicketPurchaseRequest $request, TicketPurchaseService $service)
    {
        $data = $request->input();
        $category = TicketCategory::find($data['category_id']);

        if (!$category) {
            throw new TicketCategoryNotFoundException(sprintf('Ticket Category with id: %s not found', $data['category_id']));
        }

        try {
            $response = $service->buy($request->user(), $category, $data['quantity']);
            return TicketResource::collection($response);
        } catch (\Exception $e) {
            Log::error('Ticket purchase error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
