<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 50) as $index) {
            $user = [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'image' => 'users/1688135255.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'password' => '$2y$10$PwLiNeE/XqTZSs1W6ROlWu9dmzRVLyQEFa9f6NzI/b7gqkvK6gDry',
                'phone' => $faker->unique()->randomNumber(),
                'address' => $faker->company(),
                'utype' => 1,
            ];
            DB::table('users')->insert($user);
            DB::table('user_backups')->insert($user);
        }
    }

}
