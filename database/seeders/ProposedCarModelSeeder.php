<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProposedCarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Seller_id=DB::select("select id from users where utype = 2");
        $type_id=DB::select("select id from car_types");
        for($i=0;$i<count($Seller_id);$i++){
            $Seller_id[$i]->type=$type_id[$i]->id;
        }
        $faker = Faker::create();
        foreach ($Seller_id as $index ) {
            $propose_car_models = [
                'seller_id'=>$index->id,
                'model'=>$faker->name(),
                'type'=>$index->type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('propose_car_models')->insert($propose_car_models);
        }
    }
}
