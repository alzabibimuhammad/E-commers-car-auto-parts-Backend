<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PartSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Seller_id=DB::select("select id from users where utype = 2");
        $model_id=DB::select("select id from cars");
        $category_id=DB::select("select id from categories");


        for($i=0;$i<count($Seller_id);$i++){
            $Seller_id[$i]->model_id=$model_id[$i]->id;
            $Seller_id[$i]->category_id=$category_id[$i]->id;
        }


        $faker = Faker::create();

        foreach ($Seller_id as $index ) {
            $parts = [
                'seller_id'=>$index->id,
                'name'=>'Mirror',
                'image'=>"parts/1687369480.png",
                'model_id'=>$index->model_id,
                'category_id'=>$index->category_id,
                'amount'=>50,
                'price'=>100,
                'description'=>$faker->text(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('parts')->insert($parts);
        }
    }
}
