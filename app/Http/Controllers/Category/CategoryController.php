<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends ApiController
{
    protected $empty_response_data = [];

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        // if need fix any data do:     $data['xxx'] = 'zzz';

        /** @var Model $category */
        $category = Category::create($data);

        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        $rules = [
            'name' => ['string', 'max:255'],
        ];

        $this->validate($request, $rules);

        if ($request->has('name'))
        {
            $category->name = $request->name;
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        $category->delete();

        return $this->showOne($category);
    }
}
