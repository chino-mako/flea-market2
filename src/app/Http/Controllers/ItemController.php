<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\CommentRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::where('is_sold', false);

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('auth.login')->with('message', 'マイリストを見るにはログインしてください');
            }
            $items = $user->likes()->paginate(12)->withQueryString();
        } else {
            $items = $query->paginate(12)->withQueryString();
        }

        return view('items.index', compact('items', 'tab'));
    }

    public function show($item_id)
    {
        $item = Item::withCount('likes')->findOrFail($item_id);
        return view('items.show', compact('item'));
    }

    public function toggleLike(Item $item)
    {
        $user = auth()->user();

        if ($user->likes->contains($item->id)) {
            // いいね解除
            $user->likes()->detach($item->id);
        } else {
            // いいね登録
            $user->likes()->attach($item->id);
        }

        return back();
    }

    public function create()
    {
        $categories = Category::all();

        return view('items.create', compact('categories'));
    }

    public function store(ItemRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        } else {
            $path = null;
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'brand_name' => $request->input('brand_name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'condition' => $request->input('condition'),
            'image_path' => $path,
        ]);

        $item->categories()->sync($request->input('categories'));

        return redirect()->route('items.index')->with('success', '商品を出品しました！');
    }

    public function storeComment(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $comment = new Comment();
        $comment->item_id = $item->id;
        $comment->user_id = auth()->id();
        $comment->body = $request->input('body');
        $comment->save();

        return redirect()->route('items.show', $item_id)->with('success', 'コメントを投稿しました。');
    }
}
