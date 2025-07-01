<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Clothing']);
        Category::create(['name' => 'Home Goods']);
    }
}