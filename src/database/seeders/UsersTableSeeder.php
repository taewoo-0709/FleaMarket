<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'ユーザー1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '1234567',
                'address' => '東京都千代田区1-1-1',
                'building' => 'テストビル101',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ユーザー2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '7654321',
                'address' => '大阪府大阪市2-2-2',
                'building' => '梅田タワー301',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ユーザー3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '9876543',
                'address' => '北海道札幌市3-3-3',
                'building' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ユーザー4',
                'email' => 'user4@example.com',
                'password' => Hash::make('password123'),
                'postcode' => '1122334',
                'address' => '福岡県福岡市4-4-4',
                'building' => '',
                'email_verified_at' => now(),
            ],
        ]);
    }
}
