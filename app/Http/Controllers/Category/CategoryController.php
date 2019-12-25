<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        // if need fix any data do:     $data['xxx'] = 'zzz';

        /** @var Category $category */
        $category = Category::create($data);

        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * deprecated @param  int  $id
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * deprecated @param  int  $id
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Category $category )
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:1000'],
        ];

        $this->validate($request, $rules);

        if ($request->has('name'))
        {
            $category->name = $request->name;
        }

        if ($request->has('description'))
        {
            $category->description = $request->description;
        }

        if (!$category->isDirty())
        {
            // 422 = Unprocessable Entity (malformed petition)
            $msg = 'Must supply at least one different value to update';
            return $this->errorResponse($msg, 422);
        }

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * deprecated @param  int  $id
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
    }
}
