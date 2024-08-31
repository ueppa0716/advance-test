<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        User::create([
            'name' => "管理者",
            'email' => "noreply@gmail.com",
            'authority' => 0,
            'password' => Hash::make('password'),
            'email_verified_at' => $now,
        ]);

        User::create([
            'name' => "店長A",
            'email' => "shop@gmail.com",
            'authority' => 1,
            'password' => Hash::make('password'),
            'email_verified_at' => $now,
        ]);
    }
}
