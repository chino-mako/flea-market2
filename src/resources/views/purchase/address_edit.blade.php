@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/address_edit.css') }}">
@endpush

@section('content')
<div class="address-edit-container">
    <h2 class="title">住所の変更</h2>

    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="POST" class="address-form">
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}">
            @error('postal_code')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building ?? '') }}">
            @error('building')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection
