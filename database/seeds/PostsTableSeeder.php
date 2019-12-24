<?php

use App\Section;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Post::all()->count())
        {
           Post::flushEventListeners();
           DB::statement('SET FOREIGN_KEY_CHECKS = 0');

           $admin = User::where(['email'=>'admin@admin.com'])->first();
           $users_publishers = User::where(['role' => 'role_publisher'])->get();
           $sections = Section::all();
           foreach($sections as $category)
           {
                if ($admin)
                {
                    factory(App\Post::class, 3)->create()->each(function ($post) use ($admin, $category) {
                        $post->user()->associate($admin);
                        $post->section()->associate($category);
                        $post->save();
                    });
                }
               factory(App\Post::class, 20)->create()->each(function ($post) use ($users_publishers, $category) {
                   /** Post $post */
                   $post->user()->associate($users_publishers->random());
                   $post->section()->associate($category);
                   $post->save();
               });
           }

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
