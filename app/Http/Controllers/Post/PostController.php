<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Post;

class PostController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['index','show']);
        $this->middleware('auth:api')
            ->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::all();

        return $this->showAll($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        return $this->showOne($post);
    }

}
