<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'COACHTECH') }}</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" height="30">
            </a>
        </div>

        @auth
            <div class="nav">
                <div class="search-box">
                    <form action="{{ request()->is('mylist') ? route('items.mylist') : route('items.index') }}" method="GET">
                        <input
                            type="text"
                            name="keyword"
                            value="{{ request('keyword') }}"
                            placeholder="なにをお探しですか？"
                            class="search-input"
                        >
                        <button type="submit" class="search-button">検索</button>
                    </form>
                </div>
                <div class="nav-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link">ログアウト</button>
                    </form>
                    <a href="{{ route('user.profile') }}" class="nav-link">マイページ</a>
                    <a href="{{ route('items.create') }}" class="btn">出品</a>
                </div>
            </div>
        @else
            <div class="search-box">
                <form action="{{ route('items.index') }}" method="GET">
                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="なにをお探しですか？"
                        class="search-input"
                    >
                    <button type="submit" class="search-button">検索</button>
                </form>
            </div>
            <div class="nav-right">
                <a href="{{ route('auth.login') }}" class="nav-link">ログイン</a>
                <a href="{{ route('items.create') }}" class="btn">出品</a>
            </div>
        @endauth
    </header>

    <main class="main-container">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
