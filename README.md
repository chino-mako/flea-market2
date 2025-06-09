# 環境構築
## Dockerビルド
- git clone <https://github.com/chino-mako/flea-market2>
- docker-compose up -d --build

## Laravel 環境構築
- docker-compose exec php bash
- composer install
- .env.example をコピーして.env ファイルを作成し、環境変数を変更
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan storage:link

# 使用技術
- PHP:7.3/8.0
- Laravel:8.75
- MySQL:8.0.26
- mailhog

# URL
- 開発環境: http://localhost/
- phpMyAdmin: http://localhost:8080/

# ER図
![フリマアプリER図](https://github.com/user-attachments/assets/26763287-b68c-4021-af4c-91406c2d148f)
