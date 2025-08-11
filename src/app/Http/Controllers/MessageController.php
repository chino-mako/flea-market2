<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Requests\MessageRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class MessageController extends Controller
    {
    public function store(MessageRequest $request)
    {
        $validated = $request->validated();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        }

        Message::create([
            'item_id' => $validated['item_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'メッセージを送信しました');
    }

    public function update(Request $request, Message $message)
    {
        // 認可
        if ($message->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message->content = $request->input('content');
        $message->save();

        return redirect()->back()->with('success', 'メッセージを更新しました');
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== auth()->id()) {
            abort(403, '権限がありません');
        }

        $message->delete();

        return redirect()->back()->with('success', 'メッセージを削除しました');
    }
}
