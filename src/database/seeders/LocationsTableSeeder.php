<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            'id' => '1',
            'location' => '東京都',
        ]);

        Location::create([
            'id' => '2',
            'location' => '大阪府',
        ]);

        Location::create([
            'id' => '3',
            'location' => '福岡県',
        ]);
    }
}
