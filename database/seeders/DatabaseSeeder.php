<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            SellerSeeder::class,
            ValidationSeeder::class,
            ContactUsSeeder::class,
            CarTypesSeeder::class,
            CategoriesSeeder::class,
            ModelsSeeder::class,
            ProposeCategorySeeder::class,
            ProposedCarTypeSeeder::class,
            ProposedCarModelSeeder::class,
            PartSeeders::class,
            ReportsSeeder::class,
        ]);
    }
}
