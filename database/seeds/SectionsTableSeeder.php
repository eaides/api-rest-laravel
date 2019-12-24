<?php

use App\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Section::all()->count())
        {
            Section::flushEventListeners();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            factory(Section::class)->create([
                'name' => 'desktop computers',
            ]);

            factory(Section::class)->create([
                'name' => 'laptop computers',
            ]);

            factory(Section::class)->create([
                'name' => 'mobiles and tablets',
            ]);

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
