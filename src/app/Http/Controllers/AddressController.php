<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($item_id)
    {
        $user = auth()->user()->load('address');

        if (!$user) {
            return redirect()->route('auth.login')->withErrors('ログインしてください');
        }

        if (!$user->address) {
            $user->address()->create([
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
            ]);
        }

        return view('purchase.address_edit', [
            'item_id' => $item_id,
            'address' => $user->address,
        ]);
    }

    public function update(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        $address = auth()->user()->address ?? new Address();
        $address->fill($request->only(['postal_code', 'address', 'building']));
        $address->user_id = auth()->id();
        $address->save();

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
