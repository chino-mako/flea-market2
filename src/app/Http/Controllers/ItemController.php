<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Message;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $keyword = $request->input('keyword');

        if ($tab === 'mylist') {
            $user = auth()->user();

            if ($user) {
                $likesQuery = $user->likes();

                if (!empty($keyword)) {
                    $likesQuery->where('title', 'like', '%' . $keyword . '%');
                }

                $items = $likesQuery->paginate(12)->withQueryString();
            } else {
                $items = new LengthAwarePaginator(
                    collect(),
                    0,
                    12,
                    1,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
        } else {
            $query = Item::query();

            // 自分が出品した商品を除外
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            if (!empty($keyword)) {
                $query->where('title', 'like', '%' . $keyword . '%');
            }

            $items = $query->paginate(12)->withQueryString();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show($item_id)
    {
        $item = Item::withCount('likes')->findOrFail($item_id);

        $error = null;
        if ($item->is_sold) {
            $error = 'この商品はすでに購入済みです。';
        }

        return view('items.show', compact('item', 'error'));
    }

    public function toggleLike(Item $item)
    {
        $user = auth()->user();

        if ($user->likes->contains($item->id)) {
            $user->likes()->detach($item->id);
        } else {
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
        $path = $request->hasFile('image')
            ? $request->file('image')->store('items', 'public')
            : null;

        $item = Item::create([
            'user_id'    => Auth::id(),
            'title'      => $request->input('title'),
            'brand_name' => $request->input('brand_name'),
            'description'=> $request->input('description'),
            'price'      => $request->input('price'),
            'condition'  => $request->input('condition'),
            'image_path' => $path,
        ]);

        $item->categories()->sync($request->input('categories'));

        return redirect()->route('items.index')->with('success', '商品を出品しました！');
    }

    public function storeComment(CommentRequest $request, $item_id)
    {
        $user = auth()->user();

        // コメント保存
        Comment::create([
            'item_id' => $item_id,
            'user_id' => $user->id,
            'body'    => $request->input('body'),
        ]);

        // 商品購入済みか確認
        $item = \App\Models\Item::findOrFail($item_id);
        if ($item->is_sold) {
            // 購入済み商品のコメントはチャットに反映しない
            return redirect()->route('items.show', $item_id)->with('success', 'コメントを投稿しました。');
        }

        // チャットの最初のメッセージとしても登録
        Message::create([
            'item_id'    => $item_id,
            'user_id'    => $user->id,
            'content'    => $request->input('body'),
            'image_path' => null,
        ]);

        return redirect()->route('items.show', $item_id)->with('success', 'コメントを投稿しました。');
    }
}
