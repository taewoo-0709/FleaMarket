<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            ConditionsTableSeeder::class,
            PaymentsTableSeeder::class,
            CategoriesTableSeeder::class,
            ItemsTableSeeder::class,
        ]);
    }
}
