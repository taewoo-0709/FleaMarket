<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{

    public function run()
    {

        $conditions = [
            "良好",
            "目立った傷や汚れなし",
            "やや傷や汚れあり",
            "状態が悪い",
        ];

        foreach ($conditions as $condition_kind) {
            DB::table('conditions')->insert([
                'condition_kind' => $condition_kind,
            ]);
        }
    }
}
