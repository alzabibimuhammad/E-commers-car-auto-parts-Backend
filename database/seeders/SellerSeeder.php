<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 20) as $index) {
            $user = [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'image' => 'users/1688135255.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'password' => '$2y$10$PwLiNeE/XqTZSs1W6ROlWu9dmzRVLyQEFa9f6NzI/b7gqkvK6gDry',
                'phone' => $faker->unique()->randomNumber(),
                'address' => $faker->company(),
                'utype' => 2,
            ];
            DB::table('users')->insert($user);
            DB::table('user_backups')->insert($user);
    }
    }
}
