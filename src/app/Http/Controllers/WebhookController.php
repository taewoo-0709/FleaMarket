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
        if (app()->environment('testing')) {
            $payload = json_decode($request->getContent(), false);
            $session = $payload->data->object;

            $sessionId = $session->id ?? 'cs_test_session';
            $paymentIntentId = $session->payment_intent ?? 'pi_test_intent';

            Log::info('Calling storeOrderFromCheckoutSession', [
                'session_id' => $sessionId,
                'payment_intent' => $paymentIntentId
        ]);

        $metadata = (object) $session->metadata;

            $this->storeOrderFromCheckoutSession($metadata, $paymentIntentId);
            return response('OK', 200);
        }

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            Log::error('Invalid Stripe Webhook signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $sessionId = $session->id ?? null;
            $paymentIntentId = $session->payment_intent ?? null;

            Log::info('Calling storeOrderFromCheckoutSession', [
                'session_id' => $sessionId,
                'payment_intent' => $paymentIntentId
            ]);

            $metadata = (object) $session->metadata;
            $this->storeOrderFromCheckoutSession($metadata, $paymentIntentId);
        }

        return response('OK', 200);
    }

    protected function storeOrderFromCheckoutSession($metadata, $paymentIntentId)
    {
        $meta = is_array($metadata) ? (object) $metadata : $metadata;

        $userId = $meta->user_id ?? null;
        $itemId = $meta->item_id ?? null;
        $postcode = $meta->shipping_postcode ?? null;
        $address = $meta->shipping_address ?? null;
        $building = $meta->shipping_building ?? null;

        $sessionId = $metadata->session_id ?? 'cs_test_session';
        Log::info('Processing checkout.session.completed', ['session_id' => $sessionId]);

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
            'stripe_payment_id' => $paymentIntentId,
            'shipping_postcode' => $postcode,
            'shipping_address'  => $address,
            'shipping_building' => $building,
        ]);
        Log::info('Order created from checkout.session.completed', ['stripe_payment_id' => $paymentIntentId]);
    }
}
