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

## Stripe コンビニ決済時のテスト手順
このアプリでは、Stripeのwebhook(支払い完了通知)を受けて、コンビニ決済の処理を完了させています。
ローカル開発環境でのStripe CLIを使ったテスト手順は以下の通りです。

1. Stripe CLIをインストール(未導入の場合)
https://stripe.com/docs/stripe-cli

2. Stripe CLIにログイン(初回のみ)
stripe login

3. 送信先にイベントを転送する
例：stripe listen --forward-to localhost:8000/api/webhook/stripe

成功すると、Webhook signing secret が表示されます。これを .env に設定してください：STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXX

4. CLIを使用してイベントをテスト送信する
stripe trigger payment_intent.succeeded

または、実際にCheckoutを動かして実際の支払いフローから操作確認してください。

### コンビニ決済時の処理概要
・Webhookの処理はすべてWebhookControlerにて統一しています。

・決済時にユーザーはコンビニ支払い用の番号が書かれた案内画面が表示されます。（Stripeの画面）

・支払い完了後、Stripe Webhookによってサーバー側が処理され、ordersテーブルに注文が保存されます。

・ページ遷移はありませんが、購入処理は完了しているため、商品は「Sold」状態になります。

## ER図
<img width="425" height="600" alt="スクリーンショット 2025-08-06 21 35 51" src="https://github.com/user-attachments/assets/ac90230f-e975-4237-b6b5-2774eeec8c15" />

## 使用技術 🔗

・PHP 8.1.33

・Laravel 8.83.29

・MySQL 8.0.34

・nginx 1.21.1

・mailhog

## URL

・開発環境：http://localhost/

・phpMyAdmin：http://localhost:8080/

・mailhog:http://localhost:8025/
