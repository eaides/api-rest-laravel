<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Category::all()->count())
        {
            Category::flushEventListeners();

            factory(Category::class)->create([
                'name' => 'desktop computers',
            ]);

            factory(Category::class)->create([
                'name' => 'laptop computers',
            ]);

            factory(Category::class)->create([
                'name' => 'mobiles and tablets',
            ]);
        }
    }
}
