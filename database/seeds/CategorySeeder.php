<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Category::all()->count()) {
            Category::flushEventListeners();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $quantity = 30;
            factory(App\Category::class, $quantity)->create();

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
