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
        <div class="user-icon"></div>
        <div class="user-info">
        <h2 class="user-name">{{ $user->name }}</h2>
        <a href="{{ route('user.profile.edit') }}" class="edit-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="profile-tabs">
        <a href="{{ route('user.profile', ['tab' => 'sell']) }}" class="tab {{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('user.profile', ['tab' => 'buy']) }}" class="tab {{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="items">
        @foreach ($items as $item)
        <div class="item-card">
            <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
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
            <div class="item-name">{{ $item->title }}</div>
        </div>
        @endforeach
    </div>

</div>
@endsection

