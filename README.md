# FleaMarket

## 環境構築 🔗

Docker ビルド

・  git clone git@github.com:taewoo-0709/confirmation-test.git<br>
・  docker-compose up -d --build

＊MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

Laravel 環境構築

・ docker-compose exec php bash

・ composer install

・ .env.example ファイルから.env を作成し、環境変数を変更

・ php artisan key:generate

・ php artisan migrate

・ php artisan db:seed

### ユーザー例
名前: テスト 太郎<br>
メールアドレス: test@example.com<br>
パスワード: coachtech1100

※新たに会員登録する際に使用してください。
※シーダーには別のダミーユーザーを登録済です。

## メール認証機能
mailhogを使用しています。<br>
メール認証誘導画面の、「認証はこちら」ボタンから認証画面に遷移するため、mailhogにアクセスし、届いているメールから認証コードを確認して認証画面にコードを入力して認証完了してください。

## Stripe 決済時のテスト手順
このアプリでは、Stripeのwebhook(支払い完了通知)を受けて、コンビニ決済の処理を完了させています。
カード払いも同様にwebhookを使用し、DBへの保存処理を実行しています。<br>
ローカル開発環境でのStripe CLIを使ったテスト手順は以下の通りです。

1. Stripe CLIをインストール(未導入の場合)<br>
https://stripe.com/docs/stripe-cli

2. Stripe CLIにログイン(初回のみ)<br>
stripe login

※以下からは決済機能を実行する場合、毎回行う必要があります。<br>
ターミナルを3つ準備してください。<br>

3. ターミナル①でdockerを起動させる<br>
docker-compose up -d --build

4. ターミナル②で送信先にイベントを転送する<br>
例： stripe listen --forward-to http://localhost/api/webhook/stripe <br>

※使用している環境構築によって変化することがあります。<br>
上記のstripe listenコマンドは、LaravelのDockerの環境下で、nginxの80番ポートで環境構築している場合が対象です。<br>

成功すると、Webhook signing secret が表示されます。これを .env に設定してください。<br>
例：STRIPE_WEBHOOK_SECRET=whsec_XXXXXXXXXXXXXXXXXX <br>

 ※.envファイルにSTRIPE_SECRET=sk_test_...をStripe CLIのAPIキーからコピー&ペーストしてください。<br>
 ※.envファイルを操作したらキャッシュクリアをするか、dockerの再ビルドをしてください。<br>

5. ターミナル③で stripe trigger payment_intent.succeeded を実行してください。

6. laravel.logなどでwebhook呼び出し記録を確認し、[200]が返っているか確認してください。<br>
※ログへの記載に関するコードは,app/Http/Controllers/WebhookController.phpに記述しています。

7. 開発サイトからコンビニ支払い・カード支払いを実行してください。

※コンビニ決済の場合は、4~5分ほど時間を置いて決済完了させるか、実際にCheckoutを動かして実際の支払いフローから操作確認してください。<br>
※カード支払いの場合は、一覧画面に遷移されます。

8. DBのordersテーブルに保存されているか・一覧画面にてSold表示になっているか・マイページの購入した商品に表示されているか 確認してください。


### カード・コンビニ決済時の処理概要
・Webhookの処理はすべてWebhookControlerにて統一しています。

・決済時にユーザーは<br>
①コンビニ支払いの場合、コンビニ決済用の番号が書かれた案内画面が表示されます。（Stripeの画面）

②カード支払いの場合は、カード番号の入力画面が表示されます。（Stripeの画面）<br>
カード番号は、https://docs.stripe.com/testing?locale=ja-JP を参考にしてください。<br>

例:<br>
カード番号:4242 4242 4242 4242<br>
名前: スペース区切りで名字と氏名を記入した名前なら何でも使用できます（例: Taro Taguchi）<br>
有効期限: 現在時刻より未来の年月を指定してください。<br>
セキュリティーコード: 適当な3桁か4桁の数字を入力してください。

・支払い完了後、Stripe Webhookによってサーバー側が処理され、ordersテーブルに注文が保存されます。

・コンビニ決済完了後のページ遷移はありませんが、購入処理は完了しているため、商品は「Sold」状態になります。

## テスト実施
### テスト用データベースの作成・コマンド
1. MySQLコンテナで、「demo_test」というDBを作成。
2. php artisan key:generate --env=testing を実行し、.env.testingのAPP_KEY= にアプリケーションキーを追加
3. php artisan config:clear
4. php artisan migrate --env=testing
5. php artisan test>


## ER図
<img width="422" height="597" alt="スクリーンショット 2025-08-14 20 23 56" src="https://github.com/user-attachments/assets/0ffb2d0c-0a0d-42df-9146-15077b56efb1" />

## 使用技術 🔗

・PHP 8.1.33

・Laravel 8.83.29

・MySQL 8.0.34

・nginx 1.21.1

・mailhog

・Stripe

## URL

・開発環境：http://localhost/

・phpMyAdmin：http://localhost:8080/

・mailhog:http://localhost:8025/
