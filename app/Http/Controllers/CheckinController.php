<?php

namespace App\Http\Controllers;

use App\Exceptions\TicketNotFoundException;
use App\Http\Requests\CheckinRequest;
use App\Models\Checkin;
use App\Models\Ticket;
use App\Services\CheckinService;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/check-in",
     *     operationId="checkIn",
     *     tags={"Check-in"},
     *     summary="Perform a check-in for a ticket",
     *     description="Endpoint is used to perform a check-in for a ticket by providing the ticket code.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code"},
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="ABC123XYZ",
     *                 description="Unique ticket code"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful check-in",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Check-in uspješan"),
     *             @OA\Property(property="ticket_code", type="string", example="ABC123XYZ"),
     *             @OA\Property(property="checked_at", type="string", format="date-time", example="2025-10-25T14:23:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ticket with code: ABC123XYZ not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ticket already redeemed",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ticket with code: ABC123XYZ already redeemed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="An unexpected error occurred")
     *         )
     *     ),
     *     security={
     *         {"sanctum": {}}
     *     }
     * )
     */
    public function checkIn(CheckinRequest $request, CheckinService $service)
    {
        $data = $request->input();
        $ticket = Ticket::firstWhere(['code' => $data['code']]);

        if (!$ticket) {
            throw new TicketNotFoundException(sprintf('Ticket with code: %s not found', $data['code']));
        }

        try {
            /** @var Checkin $checkIn */
            $checkIn = $service->processCheckin($request->user(), $ticket);
            return response()->json([
                'data' => [
                    [
                        'message' => 'Check-in successful',
                        'code' => $ticket->code,
                        'checked_at' => $checkIn->checked_at
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error(sprintf('Ticket code: %s check-in error', $ticket->code), [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(
                [
                    'error' => [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode()
                    ]
                ],
                $e->getCode()
            );
        }
    }
}
