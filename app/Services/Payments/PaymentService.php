<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Payments\Contracts\PaymentProviderInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    protected PaymentProviderInterface $provider;

    public function __construct(string $providerName)
    {
        $this->provider = match ($providerName) {
            'stripe'    => new Providers\StripeProvider(),
            'airwallex' => new Providers\AirwallexProvider(),
            'wspay'     => new Providers\WSPayProvider(),
            default     => throw new \Exception("Unsupported payment provider: {$providerName}"),
        };
    }

    public function createPayment(Order $order): Payment
    {
        return DB::transaction(function () use ($order) {
            // kreiraj lokalni zapis u bazi
            $payment = Payment::create([
                'order_id' => $order->id,
                'provider' => get_class($this->provider),
                'status' => 'initiated',
                'amount' => $order->total,
                'currency' => $order->currency,
                'idempotency_key' => Str::uuid(),
            ]);

            // pozovi stvarnog providera
            $response = $this->provider->createPaymentIntent($order);

            // ažuriraj podatke
            $payment->update([
                'provider_payment_id' => $response['payment_id'] ?? null,
                'status' => 'authorized',
                'provider_response' => $response,
            ]);

            return $payment;
        });
    }

    public function confirmPayment(string $providerPaymentId): void
    {
        $response = $this->provider->confirmPayment($providerPaymentId);
        // ... update order i payment status
    }
}
