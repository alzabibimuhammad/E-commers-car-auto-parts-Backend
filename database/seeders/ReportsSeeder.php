<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer_id = DB::select("select id from users where utype = 1");
        $seller_id = DB::select("select id from users where utype = 2");
        $part_id = DB::select("select id from parts");

        for ($i = 0; $i < 15; $i++) {
            $seller_id[$i]->customer_id = $customer_id[$i]->id;
            $seller_id[$i]->part_id = $part_id[$i]->id;
        }

        $faker = Faker::create();
        for ($i = 0; $i < 15; $i++) {
            $reports = [
                'customer_id' => $seller_id[$i]->customer_id,
                'seller_id' => $seller_id[$i]->id,
                'part_id' => $seller_id[$i]->part_id,
                'description' => $faker->text(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('reports')->insert($reports);
        }
    }
}
