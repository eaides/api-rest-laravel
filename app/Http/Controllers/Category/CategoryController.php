<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends ApiController
{
    protected $empty_response_data = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json(['data' => $categories],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        // if need fix any data do:     $data['xxx'] = 'zzz';

        /** @var Category $category */
        $category = Category::create($data);

        return response()->json(['data' => $category],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        return response()->json(['data' => $category],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            return response()->json(['error' => $msg, 'code' => 422],422);
        }

        $category->save();

        return response()->json(['data' => $category],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /** @var Category $category */
        $category = Category::findOrFail($id);

        $category->delete();

        return response()->json(['data' => $category],200);
    }
}
