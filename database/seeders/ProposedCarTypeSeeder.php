<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ProposedCarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Seller_id=DB::select("select id from users where utype = 2");
        $faker = Faker::create();
        foreach ($Seller_id as $index) {
            $propose_car_types = [
                'seller_id'=>$index->id,
                'type'=>$faker->name(),
                'created_at' => now(),
                'updated_at' => now(),

            ];
            DB::table('propose_car_types')->insert($propose_car_types);
        }
    }
}
