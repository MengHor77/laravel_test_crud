<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategoriesTableSeeder::class,
            // Only include seeders that exist
            // ProductsTableSeeder::class, // Uncomment if you've created this
        ]);
    }
}