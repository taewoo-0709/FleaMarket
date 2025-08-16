<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    public function index($item_id)
    {
        $user = Auth::user();

        if (empty($user->postcode) || empty($user->address)) {
            return redirect()
            ->route('profile.edit', ['item_id' => $item_id])
            ->with([
                'address_required' => true,
                'redirect_after_profile_update' => route('purchase.show', ['item_id' => $item_id]),
                'message' => '住所の登録が必要です'
            ]);
        }

        if (!session('from_address_change')) {
            session()->forget('temp_shipping_address');
        }

        session()->forget('from_address_change');

        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $payments = Payment::all();
        $shippingAddress = session('temp_shipping_address') ?? [
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
            'shipping_building' => $user->building,
        ];
        $selectedPaymentId = old('payment_id') ?: session('payment_id');

        return view('purchase', compact('item', 'user', 'payments', 'shippingAddress', 'selectedPaymentId'));
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('address_update', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session([
            'temp_shipping_address' => [
                'shipping_postcode' => $request->shipping_postcode,
                'shipping_address' => $request->shipping_address,
                'shipping_building' => $request->shipping_building,
            ],
            'from_address_change' => true,
        ]);

        return redirect("/purchase/{$item_id}");
    }

    public function confirm(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $shipping = session('temp_shipping_address') ?? [
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
            'shipping_building' => $user->building,
        ];

        $payment_id = $request->input('payment_id');
        session(['payment_id' => $payment_id]);

        if (app()->environment('testing')) {
            Order::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'stripe_payment_id' => 'pi_test_intent',
                'shipping_postcode' => $shipping['shipping_postcode'],
                'shipping_address' => $shipping['shipping_address'],
                'shipping_building' => $shipping['shipping_building'] ?? '',
            ]);
            return redirect("/purchase/{$item->id}");
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $methodType = $payment_id == 1 ? 'konbini' : 'card';

        $checkout = StripeSession::create([
            'payment_method_types' => [$methodType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'success_url' => url("/purchase/success/{$item->id}") . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url("/purchase/cancel/{$item->id}"),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'payment_id' => $payment_id,
                'shipping_postcode' => $shipping['shipping_postcode'],
                'shipping_address' => $shipping['shipping_address'],
                'shipping_building' => $shipping['shipping_building'] ?? '',
            ],
        ]);
        return redirect($checkout->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $payment_id = session('payment_id');

        $shipping = session('temp_shipping_address') ?? [
            'shipping_postcode' => $user->postcode,
            'shipping_address' => $user->address,
            'shipping_building' => $user->building,
        ];

        session()->forget(['temp_shipping_address', 'payment_id']);

        return redirect('/')->with('message', '購入が完了しました。');
    }

    public function cancel($item_id)
    {
        return redirect("/purchase/{$item_id}")->with('error', '決済がキャンセルされました。');
    }
}