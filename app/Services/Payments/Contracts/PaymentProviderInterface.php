<?php

namespace App\Services\Payments\Contracts;

use App\Models\Order;

interface PaymentProviderInterface
{
    /**
     * Initialize payment intent or session.
     */
    public function createPaymentIntent(Order $order): array;

    /**
     * Confirm payment (webhook or manual confirmation).
     */
    public function confirmPayment(string $providerPaymentId): array;

    /**
     * Handle refund (optional).
     */
    public function refund(Order $order, float $amount): array;
}
