<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
{
    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $secret = config('services.stripe.webhook_secret');

    try {
        $event = Webhook::constructEvent($payload, $sig_header, $secret);
    } catch (\Exception $e) {
        Log::error('Invalid Stripe Webhook signature: ' . $e->getMessage());
        return response('Invalid signature', 400);
    }

    Log::info('Stripe Webhook received', ['type' => $event->type]);

    try {
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $paymentIntentId = $session->payment_intent;

            Log::info('Calling storeOrderFromCheckoutSession', ['session_id' => $session->id, 'payment_intent' => $paymentIntentId]);

            $this->storeOrderFromCheckoutSession($session, $paymentIntentId);
        }
    } catch (\Exception $e) {
        Log::error('Error processing webhook: ' . $e->getMessage());
        return response('Webhook handler error', 500);
    }

    return response('OK', 200);
}

protected function storeOrderFromCheckoutSession($session, $paymentIntentId)
{
    Log::info('Processing checkout.session.completed', ['session_id' => $session->id]);

    $userId = $session->metadata->user_id ?? null;
    $itemId = $session->metadata->item_id ?? null;
    $postcode = $session->metadata->shipping_postcode ?? null;
    $address = $session->metadata->shipping_address ?? null;
    $building = $session->metadata->shipping_building ?? null;

    if (!$userId || !$itemId || !$paymentIntentId) {
        Log::warning('Missing metadata or paymentIntentId in checkout.session.completed', compact('userId', 'itemId', 'paymentIntentId'));
        return;
    }

    if (Order::where('stripe_payment_id', $paymentIntentId)->exists()) {
        Log::info('Order already exists, skipping', ['stripe_payment_id' => $paymentIntentId]);
        return;
    }

    Order::create([
        'user_id' => $userId,
        'item_id' => $itemId,
        'payment_id' => $session->metadata->payment_id ?? null,
        'stripe_payment_id' => $paymentIntentId,
        'shipping_postcode' => $postcode,
        'shipping_address' => $address,
        'shipping_building' => $building,
    ]);

    Log::info('Order created from checkout.session.completed', ['stripe_payment_id' => $paymentIntentId]);
    }
}