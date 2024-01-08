<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type_id=DB::select("select id from car_types");

        $faker = Faker::create();
        foreach ($type_id as $index ) {
            $cars = [
                'model'=>$faker->name(),
                'type_id'=>$index->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('cars')->insert($cars);
        }
    }
}
