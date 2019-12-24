<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Product::all()->count()) {
            Product::flushEventListeners();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            $quantity = 1000;
            factory(App\Product::class, $quantity)->create()->each(
                function(Product $product) {
                    $categories = App\Category::all()
                        ->random(mt_rand(1,5))
                        ->pluck('id');
                    $product->categories()->attach($categories);
                }
            );

            DB::table('category_product')->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
