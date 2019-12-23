<?php

use App\Section;
use Illuminate\Database\Seeder;

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

            factory(Section::class)->create([
                'name' => 'desktop computers',
            ]);

            factory(Section::class)->create([
                'name' => 'laptop computers',
            ]);

            factory(Section::class)->create([
                'name' => 'mobiles and tablets',
            ]);
        }
    }
}
