<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
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

        return view('purchase.show', [
            'item' => $item,
            'user' => $user,
            'image' => $image,
            'isExternal' => $isExternal,
        ]);
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // Stripe初期化
        Stripe::setApiKey(config('services.stripe.secret'));

        // Checkoutセッション作成
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->title,
                    ],
                    'unit_amount' => (int)
                    $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id]),
            'cancel_url' => route('purchase.show', ['item_id' => $item_id]),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => $user->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->buyer_id = auth()->id();
        $item->save();

        return redirect()->route('items.index')->with('success', '購入が完了しました（Stripe）');
    }
}
