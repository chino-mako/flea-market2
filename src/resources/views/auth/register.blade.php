@extends('layouts.auth')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endpush

@section('content')
<div class="form-container">
    <h1 class="form-title">会員登録</h1>

    <form class="form-box" method="POST" action="{{ route('auth.register') }}">
        @csrf

        <label for="name">ユーザー名</label>
        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}">
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}">
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" class="form-input">
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <label for="password_confirmation">確認用パスワード</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <button type="submit">登録する</button>

        <div class="login-link">
            <a href="{{ route('auth.login') }}">ログインはこちら</a>
        </div>
    </form>
</div>
@endsection
