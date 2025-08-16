<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            "コンビニ払い",
            "カード支払い",
        ];

        foreach ($methods as $payment_method) {
            Payment::create(['payment_method' => $payment_method]);
        }
    }
}
