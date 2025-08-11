<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * 配送先住所の編集画面を表示
     */
    public function edit($item_id)
    {
        $user = Auth::user();

        return view('purchase.address_edit', [
            'user' => $user,
            'item_id' => $item_id,
        ]);
    }

    /**
     * 配送先住所を更新
     */
    public function update(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        $user->postal_code = $request->postal_code;
        $user->address     = $request->address;
        $user->building    = $request->building;
        $user->save();

        return redirect()->route('purchase.show', $item_id)
                        ->with('success', '住所を更新しました');
    }
}
