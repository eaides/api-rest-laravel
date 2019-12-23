<?php

use App\User;
use Illuminate\Database\Seeder;

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
        }
    }
}
