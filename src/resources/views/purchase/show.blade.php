@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/show.css') }}">
@endpush

@section('content')
<div class="container">
    {{-- 左カラム --}}
    <div class="left-column">
        <section class="item-section">
            <div class="item-image">
                <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}" alt="商品画像">
            </div>
            <div class="item-info">
                <h1 class="item-title">{{ $item->title }}</h1>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>
        </section>

        {{-- 購入フォーム --}}
        <form id="purchase-form" action="{{ route('purchase.store', $item->id) }}" method="POST">
            @csrf

            {{-- 支払い方法 --}}
            <div class="payment-method">
                <h3>支払い方法</h3>
                <select name="payment_method">
                    <option value="">選択してください</option>
                    <option value="クレジットカード" {{ old('payment_method') == 'クレジットカード' ? 'selected' : '' }}>クレジットカード</option>
                    <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                </select>
                @error('payment_method')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            {{-- 配送先 --}}
            <div class="address">
                <h3>配送先</h3>
                @if ($user->postal_code && $user->address)
                    <p>
                        〒{{ $user->postal_code }}<br>
                        {{ $user->address }}{{ $user->building ?? '' }}
                    </p>
                    <a href="{{ route('address.edit', $item->id) }}" class="change-link">変更する</a>
                @else
                    <p>住所が未登録です。</p>
                    <a href="{{ route('address.edit', $item->id) }}" class="change-link">住所を登録する</a>
                @endif
            </div>

            {{-- 右カラムに渡す hidden input --}}
            <input type="hidden" name="price" value="{{ $item->price }}">
        </form>
    </div>

    {{-- 右カラム --}}
    <div class="right-column">
        <aside class="summary">
            <div class="summary-box">
                <div class="summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-row">
                    <span>支払い方法</span>
                    <span id="selected-payment">未選択</span>
                </div>
            </div>
            <button type="submit" form="purchase-form" class="purchase-btn">購入する</button>
        </aside>
    </div>
</div>


<script>
    const select = document.querySelector('select[name="payment_method"]');
    const selectedPayment = document.getElementById('selected-payment');

    select?.addEventListener('change', function () {
        selectedPayment.textContent = this.value || '未選択';
    });
</script>

@endsection
