<?php

use App\Section;
use App\Post;
use App\User;
use Illuminate\Database\Seeder;

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

           $admin = User::where(['email'=>'admin@admin.com'])->first();
           $users_publishers = User::where(['role' => 'role_publisher'])->get();
           $categories = Section::all();
           foreach($categories as $category)
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
        }
    }
}
