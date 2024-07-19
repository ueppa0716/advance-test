<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'id' => '1',
            'category' => '寿司',
        ]);

        Category::create([
            'id' => '2',
            'category' => '焼肉',
        ]);

        Category::create([
            'id' => '3',
            'category' => '居酒屋',
        ]);

        Category::create([
            'id' => '4',
            'category' => 'イタリアン',
        ]);

        Category::create([
            'id' => '5',
            'category' => 'ラーメン',
        ]);
    }
}
