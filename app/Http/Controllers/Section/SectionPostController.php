<?php

namespace App\Http\Controllers\Section;

use App\Helpers\Helper;
use App\Http\Controllers\ApiController;
use App\Post;
use App\Section;
use App\Transformers\PostTransformer;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SectionPostController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['index']);

        $this->middleware('transform.input:'.PostTransformer::class)
            ->only(['store','update']);
    }

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
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        /**
         * Can fix data if needed, like:
         */

        $data['image'] = null;
        if ($request->has('image'))
        {
            $data['image'] = Helper::storeAndReSizeImg($request, 'image');
        }

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
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
        ];

        $this->validate($request, $rules);

        $this->sectionVerify($section, $post);

        $post->fill($request->only([
            // status and image will be asigned later
            'title', 'content',
        ]));

        if ($request->hasFile('image'))
        {
            Storage::delete($post->image);

            $post->image = Helper::storeAndReSizeImg($request, 'image');
        }

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
     * @throws \Exception
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

        // @todo must remove only for permanently remove
        Storage::delete($post->image);

        $post->delete();

        return $this->showOne($post);
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
