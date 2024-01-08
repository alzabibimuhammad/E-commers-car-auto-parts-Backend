<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,50) as $index) {
            $Category = [
                'name'=>$faker->name(),
                'description'=>$faker->text(),
                'created_at' => now(),
                'updated_at' => now(),

            ];
            DB::table('categories')->insert($Category);
        }
    }
}
