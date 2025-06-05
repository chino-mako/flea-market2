<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $items = $user->purchasedItems;
        } else {
            $items = $user->items;

            foreach ($items as $item) {
                logger("item id: {$item->id}, user_id: {$item->user_id}, login user id: {$user->id}");
            }
        }

        return view('user.show', compact('user', 'items', 'tab'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('user.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public');

            if ($user->profile_image) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $path;
        }

        $user->name = $validated['name'];
        $user->postal_code = $validated['postal_code'];
        $user->address = $validated['address'];
        $user->building = $validated['building'] ?? null;
        $user->profile_completed = true;
        $user->save();

        return redirect()->route('items.index');
    }

    public function purchasedItems()
    {
        $user = auth()->user();
        $items = $user->purchasedItems ?? collect();
        $tab = 'buy';
        return view('user.show', compact('user', 'items', 'tab'));
    }

    public function listedItems()
    {
        $user = auth()->user();
        $items = $user->items ?? collect();
        $tab = 'sell';
        return view('user.show', compact('user', 'items', 'tab'));
    }
}
