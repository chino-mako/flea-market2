@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/purchase/show.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="left-column" style="flex: 1;">
        <section class="item-section">
            <div class="item-image">
                <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}" alt="商品画像" class="item-image" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
            <div class="item-info">
                <h1 class="item-title">{{ $item->title }}</h1>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>
        </section>

        <div class="payment-method" style="margin-top: 30px;">
            <h3>支払い方法</h3>
            <form action="{{ route('purchase.store', $item->id) }}" method="POST">
                @csrf
                <select name="payment_method" required>
                    <option value="">選択してください</option>
                    <option value="クレジットカード" {{ old('payment_method') == 'クレジットカード' ? 'selected' : '' }}>クレジットカード</option>
                    <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                </select>
                @error('payment_method')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="address" style="margin-top: 30px;">
                    <h3>配送先</h3>
                    @if ($user->postal_code)
                        <p>
                            〒{{ $user->postal_code }}<br>
                            {{ $user->address }}{{ $user->building ? $user->building : '' }}
                        </p>
                        <a href="{{ route('address.edit', $item->id) }}" class="change-link">変更する</a>
                    @else
                        <p>住所が未登録です。</p>
                        <a href="{{ route('address.edit', $item->id) }}" class="change-link">住所を登録する</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="right-column" style="width: 300px; align-self: flex-start;">
        <aside class="summary" style="border: 1px solid #ccc; padding: 20px;">
            <div class="summary-box">
                <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>
                <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>支払い方法</span>
                    <span id="selected-payment">未選択</span>
                </div>
            </div>
            <form action="{{ route('purchase.store', $item->id) }}" method="POST">
                @csrf
                <input type="hidden" name="payment_method" value="クレジットカード">
                <button type="submit" class="purchase-btn" style="margin-top: 20px; width: 100%; padding: 10px; background-color: #ff5c5c; color: white; border: none; font-weight: bold;">購入する</button>
            </form>
        </aside>
    </div>
</div>

<script>
    const select = document.querySelector('select[name="payment_method"]');
    const selectedPayment = document.getElementById('selected-payment');
    const hiddenInput = document.getElementById('payment_method_hidden');

    select.addEventListener('change', function() {
        selectedPayment.textContent = this.value || '未選択';
        hiddenInput.value = this.value;
    });
</script>
@endsection
