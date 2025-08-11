<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'COACHTECH') }}</title>
</head>
<body>
    <p>{{ $fromUser->name }} さんが「{{ $item->title }}」の取引を完了しました。</p>
    <p>評価を確認してみてください！</p>
</body>
</html>