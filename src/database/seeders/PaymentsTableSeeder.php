<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{

    public function run()
    {

        $methods = [
            "コンビニ払い",
            "カード支払い",
        ];

        foreach ($methods as $payment_method) {
            DB::table('payments')->insert([
                'payment_method' => $payment_method,
            ]);
        }
    }
}
