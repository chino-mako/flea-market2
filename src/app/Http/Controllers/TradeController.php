<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    public function show(Item $item)
    {
        $user = Auth::user();
        $role = ($item->user->id === $user->id) ? 'seller' : 'buyer';

        // メッセージ取得（ユーザー付き）
        $messages = $item->messages()->with('user')->get();

        // 取引相手をメッセージ履歴から取得
        $lastMessage = $messages->where('user_id', '!=', $user->id)->last();
        $partnerUser = $lastMessage ? $lastMessage->user : null;

        // その他の取引
        $userItems = Item::with('messages')
        ->tradingForUser($user->id)
        ->get();

        $userItems->map(function ($item) use ($user) {
            $item->unread_count = $item->messages
                ->where('user_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
            return $item;
        });

        // 未読メッセージを既読に
        Message::where('item_id', $item->id)
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('trades.show', compact('item', 'messages', 'partnerUser', 'userItems', 'user', 'role'));
    }

    public function complete(Item $item)
    {
        $item->update(['is_completed' => true]);

        return redirect()
            ->route('trades.show', $item->id)
            ->with('showRatingModal', true);
    }
}
