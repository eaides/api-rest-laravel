<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Product  $product
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @param  \App\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product, Category $category)
    {
        // sync     - replace actual categories and attach the given one(s)
        //      $product->categories()->sync([$category->id]);
        // attach   - add to the actual categories, but do not check for duplicates
        //      $product->categories()->attach([$category->id]);
        // syncWithoutDetaching - like sync (add) but no dettach actual
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->index($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @param  \App\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id))
        {
            return $this->errorResponse('The specified category is not attached to the product', 404);
        }
        $product->categories()->detach([$category->id]);

        return $this->index($product);
    }
}
