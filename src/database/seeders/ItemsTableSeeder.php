<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;


class ItemsTableSeeder extends Seeder
{

    public function run()
    {

        $category_1 = Category::where('category_name', 'ファッション')->first();
        $category_2 = Category::where('category_name', '家電')->first();
        $category_5 = Category::where('category_name', 'メンズ')->first();
        $category_6 = Category::where('category_name', 'コスメ')->first();
        $category_10 = Category::where('category_name', 'キッチン')->first();

        $condition_verygood = Condition::where('condition_kind', '良好')->first();
        $condition_good = Condition::where('condition_kind', '目立った傷や汚れなし')->first();
        $condition_soso = Condition::where('condition_kind', 'やや傷や汚れあり')->first();
        $condition_bad = Condition::where('condition_kind', '状態が悪い')->first();

        $items = [
            [
                'user_id' => 1,
                'title' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'item_explain' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'items/item1.jpg',
                'condition_id' => $condition_verygood->id,
                'category_id' => 5,
            ],

            [
                'user_id' => 2,
                'title' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'item_explain' => '高速で信頼性の高いハードディスク',
                'image_url' => 'items/item2.jpg',
                'condition_id' => $condition_good->id,
                'category_id' => 2,
            ],

            [
                'user_id' => 3,
                'title' => '玉ねぎ',
                'price' => 300,
                'brand' => 'なし',
                'item_explain' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'items/item3.jpg',
                'condition_id' => $condition_soso->id,
                'category_id' => 10,
            ],

            [
                'user_id' => 4,
                'title' => '革靴',
                'price' => 4000,
                'brand' => '',
                'item_explain' => 'クラシックなデザインの革靴',
                'image_url' => 'items/item4.jpg',
                'condition_id' => $condition_bad->id,
                'category_id' => 1,
            ],

            [
                'user_id' => 1,
                'title' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'item_explain' => '高性能なノートパソコン',
                'image_url' => 'items/item5.jpg',
                'condition_id' => $condition_verygood->id,
                'category_id' => 2,
            ],

            [
                'user_id' => 2,
                'title' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'item_explain' => '高音質のレコーディング用マイク',
                'image_url' => 'items/item6.jpg',
                'condition_id' => $condition_good->id,
                'category_id' => 2,
            ],

            [
                'user_id' => 3,
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'item_explain' => 'おしゃれなショルダーバッグ',
                'image_url' => 'items/item7.jpg',
                'condition_id' => $condition_soso->id,
                'category_id' => 1,
            ],

            [
                'user_id' => 4,
                'title' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'item_explain' => '使いやすいタンブラー',
                'image_url' => 'items/item8.jpg',
                'condition_id' => $condition_bad->id,
                'category_id' => 10,
            ],

            [
                'user_id' => 1,
                'title' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'item_explain' => '手動のコーヒーミル',
                'image_url' => 'items/item9.jpg',
                'condition_id' => $condition_verygood->id,
                'category_id' => 10,
            ],

            [
                'user_id' => 2,
                'title' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'item_explain' => '便利なメイクアップセット',
                'image_url' => 'items/item10.jpg',
                'condition_id' => $condition_good->id,
                'category_id' => 6,
            ],
        ];

        DB::table('items')->insert($items);
    }
}
