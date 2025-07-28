<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            "ファッション",
            "家電",
            "インテリア",
            "レディース",
            "メンズ",
            "コスメ",
            "本",
            "ゲーム",
            "スポーツ",
            "キッチン",
            "ハンドメイド",
            "アクセサリー",
            "おもちゃ",
            "ベビー・キッズ",
        ];

        foreach ($categories as $category_name) {
            DB::table('categories')->insert([
                'category_name' => $category_name,
            ]);
        }
    }
}
