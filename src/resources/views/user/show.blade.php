@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/user/show.css') }}">
@endpush

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="profile-container">
    <div class="profile-header">
        <div class="user-icon">
            @if ($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="プロフィール画像" class="profile-img">
            @else
                <img src="{{ asset('images/default-user.png') }}" alt="デフォルト画像" class="profile-img">
            @endif
        </div>

        <div class="user-info">
            <div class="user-name">{{ $user->name }}</div>
            @if ($ratingCount > 0)
                <div class="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($ratingAvg))
                            <span class="star filled">&#9733;</span>
                        @else
                            <span class="star">&#9733;</span>
                        @endif
                    @endfor
                </div>
            @endif
        </div>

        <a href="{{ route('user.profile.edit') }}" class="edit-button-outline">プロフィールを編集</a>
    </div>

    <div class="profile-tabs">
        <a href="{{ route('user.profile', ['tab' => 'sell']) }}" class="tab {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('user.profile', ['tab' => 'buy']) }}" class="tab {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
        <a href="{{ route('user.profile', ['tab' => 'trade']) }}" class="tab {{ $tab === 'trade' ? 'active' : '' }}">
            取引中の商品
            @if ($tradeItemCount > 0)
                <span class="tab-badge">{{ $tradeItemCount }}</span>
            @endif
        </a>
    </div>

    <div class="items">
    @foreach ($items as $item)
        <div class="item-card">
            @php
                $unread = $item->unreadMessagesCountForUser(auth()->id());
            @endphp

            @if ($tab === 'trade')
                {{-- 取引チャット画面へ --}}
                <a href="{{ route('trades.show', ['item' => $item->id]) }}">
            @else
                {{-- 商品詳細画面へ --}}
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
            @endif

                <div class="item-image">
                @if ($tab === 'trade' && $unread > 0)
                    <div class="item-badge">{{ $unread }}</div>
                @endif

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
                <div class="item-name">{{ $item->title }}</div>
            </a>
        </div>
    @endforeach
    </div>
</div>
@endsection

