<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
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
         * products<->seller is a relation one to many
         * therefore the result will be a unique collection
         * do not use collapse()
         *
         * products<->categories is a relation many to many
         * therefore the result will be a collection of collections
         * then MUST use collapse() to unify those collections in one
         */
        $sellers = $buyer->transactions()
            ->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();

        return $this->showAll($sellers);
    }

}
