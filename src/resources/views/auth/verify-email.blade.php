@extends('layouts.auth')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endpush

@section('content')
<div class="container">
    <p class="message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>
    <a href="#" class="verify-button">認証はこちらから</a>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="submit">認証メールを再送する</button>
    </form>
</div>
@endsection
