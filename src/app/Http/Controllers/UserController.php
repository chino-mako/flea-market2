<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Rating;

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
        $unreadTotal = 0;
        $ratings = $user->receivedRatings();
        $ratingCount = $ratings->count();

        $ratingAvg = $ratingCount > 0 ? round($ratings->avg('score')) : null;

        $tradeItems = Item::where('is_completed', false)
            ->where(function ($query) use ($user) {
                $query
                    ->whereHas('purchases', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                        ->whereHas('messages');
                    })
                    ->orWhereHas('messages', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->get();

        $tradeItemCount = $tradeItems->count();

        if ($tab === 'buy') {
            $items = Item::where('is_completed', true)
            ->whereHas('purchases', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();
        } elseif ($tab === 'trade') {
            $items = $tradeItems->load(['messages' => function ($q) {
                $q->latest();
            }])->sortByDesc(function ($item) {
                return optional($item->messages->first())->created_at;
            });

            $unreadTotal = $items->sum(function ($item) use ($user) {
                return $item->messages
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count();
            });
        }
        else {
            $items = $user->items;
        }

        return view('user.show', compact('user', 'items', 'tab', 'tradeItemCount', 'unreadTotal', 'ratingAvg', 'ratingCount'));
    }


    public function edit()
    {
        $user = Auth::user();

        $files = Storage::files('public/profile_images');
        $images = array_map(fn($path) => str_replace('public/', '', $path), $files);

        return view('user.edit', compact('user', 'images'));
    }

    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $path = $file->store('profile_images', 'public');

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $path;
        }

        $user->fill([
            'name'         => $validated['name'],
            'postal_code'  => $validated['postal_code'],
            'address'      => $validated['address'],
            'building'     => $validated['building'] ?? null,
        ])->save();

        return redirect()->route('items.index');
    }

    public function purchasedItems()
    {
        $user = Auth::user();
        $items = $user->purchasedItems ?? collect();
        $tab = 'buy';

        return view('user.show', compact('user', 'items', 'tab'));
    }

    public function listedItems()
    {
        $user = Auth::user();
        $items = $user->items ?? collect();
        $tab = 'sell';

        return view('user.show', compact('user', 'items', 'tab'));
    }
}
