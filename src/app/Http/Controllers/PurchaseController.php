<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        $image = $item->image_path;
        $isExternal = Str::startsWith($image, ['http://', 'https://']);
        $user = auth()->user();

        return view('purchase.show', compact('item', 'user', 'image', 'isExternal'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = $request->input('payment_method');
        $allowedMethods = [
            'クレジットカード' => ['card'],
            'コンビニ払い'     => ['konbini'],
        ];

        if (!isset($allowedMethods[$paymentMethod])) {
            abort(400, '不正な支払い方法です');
        }

        $session = Session::create([
            'payment_method_types' => $allowedMethods[$paymentMethod],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => (int) $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id]),
            'cancel_url' => route('purchase.show', ['item_id' => $item_id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
                'payment_method' => $paymentMethod,
            ],
        ]);

        return redirect($session->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->is_sold) {
            return redirect()->route('items.index')->with('error', 'この商品はすでに購入済みです。');
        }

        Purchase::create([
            'item_id'          => $item->id,
            'user_id'          => auth()->id(),
            'payment_method'   => 'カード払い', // ※将来的にWebhookで取得すると良い
            'stripe_payment_id'=> '',          // ※Webhookから取得推奨
            'is_paid'          => true,
        ]);

        $item->is_sold = true;
        $item->save();

        return redirect()->route('items.index')->with('success', '購入が完了しました（Stripe）');
    }
}
