<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;


class ItemsTableSeeder extends Seeder
{

    public function run()
    {
        $condition_verygood = Condition::where('condition_kind', '良好')->first();
        $condition_good = Condition::where('condition_kind', '目立った傷や汚れなし')->first();
        $condition_soso = Condition::where('condition_kind', 'やや傷や汚れあり')->first();
        $condition_bad = Condition::where('condition_kind', '状態が悪い')->first();

        $userIds = User::pluck('id')->toArray();

        $items = [
            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'item_explain' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'items/item1.jpg',
                'condition_id' => $condition_verygood->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'item_explain' => '高速で信頼性の高いハードディスク',
                'image_url' => 'items/item2.jpg',
                'condition_id' => $condition_good->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => '玉ねぎ',
                'price' => 300,
                'brand' => 'なし',
                'item_explain' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'items/item3.jpg',
                'condition_id' => $condition_soso->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => '革靴',
                'price' => 4000,
                'brand' => '',
                'item_explain' => 'クラシックなデザインの革靴',
                'image_url' => 'items/item4.jpg',
                'condition_id' => $condition_bad->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'item_explain' => '高性能なノートパソコン',
                'image_url' => 'items/item5.jpg',
                'condition_id' => $condition_verygood->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'item_explain' => '高音質のレコーディング用マイク',
                'image_url' => 'items/item6.jpg',
                'condition_id' => $condition_good->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'item_explain' => 'おしゃれなショルダーバッグ',
                'image_url' => 'items/item7.jpg',
                'condition_id' => $condition_soso->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'item_explain' => '使いやすいタンブラー',
                'image_url' => 'items/item8.jpg',
                'condition_id' => $condition_bad->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'item_explain' => '手動のコーヒーミル',
                'image_url' => 'items/item9.jpg',
                'condition_id' => $condition_verygood->id,
            ],

            [
                'user_id' => $this->getRandomUserId($userIds),
                'title' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'item_explain' => '便利なメイクアップセット',
                'image_url' => 'items/item10.jpg',
                'condition_id' => $condition_good->id,
            ],
        ];

        DB::table('items')->insert($items);
    }
    private function getRandomUserId(array $userIds)
    {
        return $userIds[array_rand($userIds)];
    }
}
