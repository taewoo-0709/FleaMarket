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

## メール認証機能
mailhogを使用しています。
メール認証誘導画面の、「認証はこちら」ボタンからmailhogに遷移するため、mailhogに届いているメールから認証作業をしてください。

## Stripe コンビニ決済時のテスト手順
このアプリでは、Stripeのwebhook(支払い完了通知)を受けて、コンビニ決済の処理を完了させています。
ローカル開発環境でのStripe CLIを使ったテスト手順は以下の通りです。

1. Stripe CLIをインストール(未導入の場合)
https://stripe.com/docs/stripe-cli

2. Stripe CLIにログイン(初回のみ)
stripe login

3. ターミナル①でdockerを起動させる
docker-compose up -d --build

4. ターミナル②で送信先にイベントを転送する
例：stripe listen --forward-to api/webhook/stripe

成功すると、Webhook signing secret が表示されます。これを .env に設定してください：STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXX

 ※.envファイルにSTRIPE_SECRET=sk_test_...をStripeのAPIキーからコピー&ペーストしてください。

 ※.envファイルを操作したらキャッシュクリアをするか、dockerの再ビルドをしてください。

5. ターミナル③でstripe trigger payment_intent.succeededを実行してください。

6. laravel.logなどでwebhook呼び出し記録を確認し、[200]が返っているか確認してください。
※laravel.logなどログを使用する場合は、WebhookController.phpでコメントアウトになっている「Log::~」を使用できるようにしてからログ確認をしてください。8箇所あります。

※ログを使用せず、ターミナル②で確認する場合は、コメントアウトのままでも確認ができます。[200]が返っていたら成功です。

7. 開発サイトからコンビニ支払いを実行してください。

※4~5分ほど時間を置いて決済完了させるか、実際にCheckoutを動かして実際の支払いフローから操作確認してください。

8. DBのordersテーブルに保存されているか確認してください。

## カード支払い
コンビニ支払い手順の1~3までが済んでいれば、購入画面から進めるかと思います。


### カード・コンビニ決済時の処理概要
・Webhookの処理はすべてWebhookControlerにて統一しています。

・決済時にユーザーは
①コンビニ支払いの場合、コンビニ決済用の番号が書かれた案内画面が表示されます。（Stripeの画面）

②カード支払いの場合は、カード番号の入力画面が表示されます。（Stripeの画面）
カード番号は、https://docs.stripe.com/testing?locale=ja-JP を参考にしてください。

・支払い完了後、Stripe Webhookによってサーバー側が処理され、ordersテーブルに注文が保存されます。

・コンビニ決済完了後のページ遷移はありませんが、購入処理は完了しているため、商品は「Sold」状態になります。


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
