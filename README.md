# FleaMarket

## 環境構築 🔗

Docker ビルド

・  git clone git@github.com:taewoo-0709/confirmation-test.git
・  docker-compose up -d --build

＊MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

Laravel 環境構築

・ docker-compose exec php bash

・ composer install

・ .env.example ファイルから.env を作成し、環境変数を変更

・ php artisan key:generate

・ php artisan migrate

・ php artisan db:seed

## ER図
・URL:/Users/oharuneru/coachtech/laravel/mockcase/flea-market/src/diagrams.drawio.png


## 使用技術 🔗

・PHP 8.1.33

・Laravel 8.83.29

・MySQL 8.0.34

・nginx 1.21.1

## URL

・開発環境：http://localhost/

・phpMyAdmin：http://localhost:8080/
