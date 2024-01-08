<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
            $user = [
                'id'=>'0',
                'name' =>'Muhammad Alzabibi',
                'email' => 'admin@a.a',
                'image' => 'users/1688135255.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'password' => '$2y$10$PwLiNeE/XqTZSs1W6ROlWu9dmzRVLyQEFa9f6NzI/b7gqkvK6gDry',
                'phone' => '0932392808',
                'address' => 'abaseen',
                'utype' => 0,
            ];
            DB::table('users')->insert($user);
            DB::table('user_backups')->insert($user);
    }
}
