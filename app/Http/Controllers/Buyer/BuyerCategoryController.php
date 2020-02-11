<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Buyer  $buyer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        /**
         * products<->categories is a relation many to many
         * therefore the result will be a collection of collections
         * then MUST use collapse() to unify those collections in one
         *
         * products<->seller is a relation one to many
         * therefore the result will be a unique collection
         * do not use collapse()
         */
        $categories = $buyer->transactions()
            ->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();

        return $this->showAll($categories);
    }

}
