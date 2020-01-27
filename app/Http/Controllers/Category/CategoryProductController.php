<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;

class CategoryProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Category  $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $products = $category->products;

        return $this->showAll($products);
    }

}
