<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 50) as $index) {
            $user = [
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'message'=>$faker->text(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('contact_us')->insert($user);
        }

    }
}
