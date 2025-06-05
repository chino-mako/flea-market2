<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'COACHTECH') }}</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/auth.css') }}">
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

    </header>

    <main class="main-container">
        @yield('content')
    </main>

</body>
</html>
