@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/trades/show.css') }}">
@endpush

@section('content')
<div class="trade-container">
    <aside class="trade-sidebar">
        <h2>その他の取引</h2>
        @foreach ($userItems as $userItem)
            <a href="{{ route('trades.show', $userItem->id) }}" class="trade-link">
                {{ $userItem->title }}
            </a>
        @endforeach
    </aside>

    <section class="trade-main">
        <div class="trade-header">
            <div class="trade-user">
                @php
                    $loggedInUser = auth()->user();
                    $seller = $item->user; // 出品者
                    $buyer = $messages
                        ->pluck('user')
                        ->firstWhere('id', '!=', $seller->id); // 最初にコメントした購入者
                @endphp

                @if ($loggedInUser->id === $seller->id)
                    {{-- 出品者の場合：購入者の名前を表示 --}}
                    @if ($buyer)
                        <img src="{{ asset('storage/' . $buyer->profile_image) }}" class="user-icon" alt="プロフィール画像">
                        <span>「{{ $buyer->name }}」さんとの取引画面</span>
                    @else
                        <span>購入者情報がありません</span>
                    @endif
                @else
                    {{-- 出品者以外（購入者）：出品者の名前を表示 --}}
                    <img src="{{ asset('storage/' . $seller->profile_image) }}" class="user-icon" alt="プロフィール画像">
                    <span>「{{ $seller->name }}」さんとの取引画面</span>
                @endif
            </div>
            <form action="{{ route('trades.complete', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-complete">取引を完了する</button>
            </form>
        </div>

        <div class="trade-item">
            <div class="item-image">
                    @if($item->image_path)
                        @php
                            $image = $item->image_path;
                            $isExternal = Str::startsWith($image, ['http://', 'https://']);
                        @endphp
                        <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}" alt="{{ $item->title }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="no image">
                    @endif
                </div>
            <div class="item-info">
                <h3>{{ $item->title }}</h3>
                <p>{{ number_format($item->price) }}円</p>
            </div>
        </div>

        <div class="chat-box">
            @foreach ($messages as $message)
                <div class="chat-message {{ $message->user_id === auth()->id() ? 'own-message' : 'other-message' }}">
                <div class="chat-user">
                    @if ($message->user->profile_image)
                        <img src="{{ asset('storage/' . $message->user->profile_image) }}" alt="プロフィール画像" class="user-icon">
                    @else
                        <img src="{{ asset('images/default-user.png') }}" alt="デフォルト画像" class="user-icon">
                    @endif
                    <span>{{ $message->user->name }}</span>
                </div>
                <div class="chat-content">
                    @if ($message->user_id === auth()->id())
                        <div class="chat-actions">
                            <a href="#" class="btn-action edit-toggle" data-id="{{ $message->id }}">編集</a>
                            <form action="{{ route('messages.destroy', $message->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </div>

                        <!-- 編集フォーム（初期は非表示） -->
                        <form id="edit-form-{{ $message->id }}" action="{{ route('messages.update', $message->id) }}" method="POST" class="edit-form" style="display:none;">
                            @csrf
                            @method('PUT')
                            <input type="text" name="content" value="{{ $message->content }}">
                            <button type="submit" class="btn-action">更新</button>
                        </form>
                    @endif

                    <p>{{ $message->content }}</p>
                </div>

                </div>
            @endforeach
        </div>

        <form action="{{ route('trades.message', $item->id) }}" method="POST" enctype="multipart/form-data" class="chat-form">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">

            <input type="text" name="content" placeholder="{{ $errors->has('content') ? $errors->first('content') : '取引メッセージを記入してください' }}" value="{{ old('content') }}" class="{{ $errors->has('content') ? 'input-error' : '' }}">

            <label for="image-upload" class="btn-image">画像を追加</label>
            <input type="file" name="image" id="image-upload" style="display: none;">
            @if ($errors->has('image'))
                <p class="error-message">{{ $errors->first('image') }}</p>
            @endif
            <button type="submit" class="btn-send">
                <img src="{{ asset('images/send.jpg') }}" alt="送信">
            </button>
        </form>

    </section>
</div>

<!-- 評価モーダル -->
<!-- 評価モーダル -->
<div id="rating-modal" class="modal" style="display:none;">
    <div class="modal-card">
        <p class="modal-title">取引が完了しました。</p>
        <p class="modal-subtitle">今回の取引相手はどうでしたか？</p>
        <div class="stars">
            @for ($i = 1; $i <= 5; $i++)
                <input type="radio" id="star{{ $i }}" name="score" value="{{ $i }}" hidden>
                <label for="star{{ $i }}" class="star">&#9733;</label>
            @endfor
        </div>
        <form action="{{ route('ratings.store', $item->id) }}" method="POST" class="rating-form">
            @csrf
            <input type="hidden" name="score" id="selected-score" value="0">
            <button type="submit" class="btn-send-rating">送信する</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="content"]');
    const storageKey = 'chat_draft_{{ $item->id }}'; // 商品別にキーを分ける

    // 1. 入力内容の保存（リアルタイム）
    input.addEventListener('input', function () {
        sessionStorage.setItem(storageKey, input.value);
    });

    // 2. ページ読み込み時に復元
    const saved = sessionStorage.getItem(storageKey);
    if (saved) {
        input.value = saved;
    }

    // 3. 送信時に保存内容をクリア
    const form = document.querySelector('.chat-form');
    form.addEventListener('submit', function () {
        sessionStorage.removeItem(storageKey);
    });
});

document.querySelectorAll('.edit-toggle').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.dataset.id;
        const form = document.getElementById('edit-form-' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const scoreInput = document.getElementById('selected-score');

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = 5 - index;
            scoreInput.value = rating;

            stars.forEach((s, i) => {
                s.classList.toggle('selected', i >= index);
            });
        });
    });

    // 評価モーダルの表示制御
    @if (session('showRatingModal'))
        document.getElementById('rating-modal').style.display = 'flex';
    @endif
});

</script>
@endpush
