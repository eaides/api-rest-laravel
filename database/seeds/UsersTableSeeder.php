<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::all()->count())
        {
            User::flushEventListeners();
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            factory(User::class)->create([
                'name' => 'admin',
                'surname' => 'admin',
                'role' => 'role_admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ]);

            factory(App\User::class, 25)->create([
                'role' => 'role_publisher',
            ]);

            factory(App\User::class, 25)->create([
                'role' => 'role_reader',
            ]);

            factory(App\User::class, 2)->create([
                'role' => 'role_admin',
            ]);

            factory(App\User::class, 2)->create([
                'role' => 'role_bad',
            ]);

            $quantity = 500;
            factory(App\User::class, $quantity)->create();

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
