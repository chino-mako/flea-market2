@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endpush

@section('content')
<div class="item-detail">
    @php
    use Illuminate\Support\Str;
    $image = $item->image_path;
    $isExternal = Str::startsWith($image, ['http://', 'https://']);
    @endphp

    <div class="image-section">
        <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}" alt="商品画像" class="item-image">
    </div>

    <div class="info-section">
        <h1 class="item-title">{{ $item->title }}</h1>
        <p class="brand-name">{{ $item->brand_name }}</p>
        <p class="price">¥{{ number_format($item->price) }} <span class="tax">（税込）</span></p>

        <div class="item-actions">
            <div class="icon-group">
                {{-- コメント数 --}}
                <div class="icon">
                    💬 <span class="count">{{ $item->comments->count() }}</span>
                </div>

                {{-- いいねボタン（ログインユーザーのみトグル可能） --}}
                <div class="icon">
                    @auth
                        <form action="{{ route('items.toggleLike', $item->id) }}" method="POST" class="like-toggle-form">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-middle {{ auth()->user()->likes->contains($item->id) ? 'text-danger' : 'text-primary' }}">
                                ⭐ <span class="count">{{ $item->likes_count ?? 0 }}</span>
                            </button>
                        </form>
                    @else
                        {{-- 未ログイン時は表示のみ --}}
                        ⭐ <span class="count">{{ $item->likes_count ?? 0 }}</span>
                    @endauth
                </div>
            </div>
        </div>


        {{-- 購入ボタン --}}
        <a href="{{ route('purchase.show', $item->id) }}" class="buy-button">購入手続きへ</a>

        {{-- 商品説明 --}}
        <section class="description">
            <h2>商品説明</h2>
            <p>カラー：{{ $item->color }}</p>
            <p>{{ $item->description }}</p>
        </section>

        {{-- 商品情報 --}}
        <section class="info">
            <h2>商品の情報</h2>
            <p>カテゴリー：
                @foreach($item->categories as $category)
                    <span class="tag">{{ $category->name }}</span>
                @endforeach
            </p>
            <p>商品の状態：{{ $item->condition }}</p>
        </section>

        {{-- コメント一覧 --}}
        <section class="comments">
            <h2>コメント({{ $item->comments->count() }})</h2>

            @foreach($item->comments as $comment)
                <div class="comment">
                    <div class="user-icon"></div>
                    <div class="comment-body">
                        <p class="username">{{ $comment->user->name }}</p>
                        <p class="text">{{ $comment->body }}</p>
                    </div>
                </div>
            @endforeach

            {{-- コメント投稿フォーム --}}
            <form action="{{ route('comment.store', $item->id) }}" method="POST">
                @csrf
                <label for="comment">商品へのコメント</label>
                <textarea name="body" id="comment" rows="4" >{{ old('body') }}</textarea>
                @error('body')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <button type="submit" class="comment-submit">コメントを送信する</button>
            </form>

        </section>
    </div>
</div>
@endsection
