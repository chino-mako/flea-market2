@extends('layouts.auth')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h1 class="form-title">ログイン</h1>

    <form action="{{ route('login') }}" method="POST" class="form-box">
        @csrf

        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}">
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" class="form-input">
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <button type="submit" class="form-button">ログインする</button>
    </form>

    <div class="form-link">
        <a href="{{ route('auth.register') }}">会員登録はこちら</a>
    </div>
</div>
@endsection
