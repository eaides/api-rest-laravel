<?php

namespace App\Http\Controllers\Section;

use App\Http\Controllers\ApiController;
use App\Post;
use App\Section;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SectionPostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Section  $section
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Section $section)
    {
        $posts = $section->posts;

        return $this->showAll($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function store(Request $request, Section $section)
    {
        // meanwhile use User 1
        // @todo take from Autehnticated user
        $user = User::find(1);
        // meanwhile use User 1

        if (!$user)
        {
            throw new AuthenticationException();
        }
        if (!$user->isPublisher())
        {
            throw new UnauthorizedException();
        }

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
            'image' => ['image'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        /**
         * Can fix data if needed, like:
         */

        if ($request->has('image'))
        {
            // @todo
        }
        else
        {
            $data['image'] = null;
        }
        $data['image'] = null;  // @todo: remove this row when will process the files

        $data['section_id'] = $section->id;
        $data['user_id'] = $user->id;

        $post = Post::create($data);

        return $this->showOne($post, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @param  \App\Post     $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function update(Request $request, Section $section, Post $post)
    {
        // meanwhile use User 1
        // @todo take from Autehnticated user
        $user = User::find(1);
        // meanwhile use User 1

        if (!$user)
        {
            throw new AuthenticationException();
        }
        if (!$user->isPublisher())
        {
            throw new UnauthorizedException();
        }

        $rules = [
            'title' => ['string', 'max:255'],
            'image' => ['image'],
        ];

        $this->validate($request, $rules);

        $this->sectionVerify($section, $post);

        $post->fill($request->only([
            // status and image will be asigned later
            'title', 'content',
        ]));

        if ($request->has('image'))
        {
            // @todo
        }
        else
        {
            $post->image = null;
        }
        $post->image = null;  // @todo: remove this row when will process the files

        if ($post->isClean())
        {
            return $this->errorUpdateNoChanges();
        }

        $post->save();

        return $this->showOne($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @param  \App\Post     $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function destroy(Section $section, Post $post)
    {
        // meanwhile use User 1
        // @todo take from Autehnticated user
        $user = User::find(1);
        // meanwhile use User 1

        if (!$user)
        {
            throw new AuthenticationException();
        }
        if (!$user->isPublisher())
        {
            throw new UnauthorizedException();
        }

        $this->sectionVerify($section, $post);

    }

    /**
     * @param Section $section
     * @param Post $post
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function sectionVerify(Section $section, Post $post)
    {
        if ($post->secton_id != $section->id)
        {
            throw new HttpException(422,"The section specified is not the real post's section");
        }
    }

}
