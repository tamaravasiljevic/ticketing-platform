<?php

namespace App\Services\Payments\Providers;

use App\Models\Order;
use App\Services\Payments\Contracts\PaymentProviderInterface;
use Stripe\StripeClient;

class StripeProvider implements PaymentProviderInterface
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Order $order): array
    {
        $intent = $this->stripe->paymentIntents->create([
            'amount' => intval($order->total * 100),
            'currency' => strtolower($order->currency),
            'metadata' => [
                'order_id' => $order->id,
                'reference' => $order->reference,
            ],
        ]);

        return [
            'payment_id' => $intent->id,
            'client_secret' => $intent->client_secret,
        ];
    }

    public function confirmPayment(string $providerPaymentId): array
    {
        $intent = $this->stripe->paymentIntents->retrieve($providerPaymentId);

        return [
            'status' => $intent->status,
            'amount' => $intent->amount / 100,
        ];
    }

    public function refund(Order $order, float $amount): array
    {
        $refund = $this->stripe->refunds->create([
            'payment_intent' => $order->payment->provider_payment_id,
            'amount' => intval($amount * 100),
        ]);

        return ['status' => $refund->status];
    }
}
