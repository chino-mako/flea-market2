<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompleted;

class RatingController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $item = Item::findOrFail($itemId);
        $fromUser = Auth::user();
        $toUser = $item->user->id === $fromUser->id
                    ? $item->messages()->first()->user
                    : $item->user;

        Rating::create([
            'item_id' => $itemId,
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'score' => $request->score,
        ]);

        // メール通知（出品者宛）
        Mail::to($toUser->email)->send(new TradeCompleted($item, $fromUser));

        return redirect()->route('items.index')->with('success', '取引と評価が完了しました');
    }
}
