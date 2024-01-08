<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProposeCategorySeeder extends Seeder
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
            $ProposedCategory = [
                'seller_id'=>$index->id,
                'name'=>$faker->name(),
                'description'=>$faker->text(),
                'created_at' => now(),
                'updated_at' => now(),

            ];
            DB::table('proposecategories')->insert($ProposedCategory);
        }
    }
}
