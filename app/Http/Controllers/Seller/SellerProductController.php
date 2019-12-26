<?php

namespace App\Http\Controllers\Seller;

use App\Product;
use App\Seller;
use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Seller  $seller
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $seller
     *      we will use User instead Seller because can be
     *      that a User yet create any product (remember: Seller
     *      -that extends User- IS an User with at least one product
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'quantity' => ['required','integer','min:1'],
            'status' => ['in:'.Product::PRODUCT_AVAILABLE.','.Product::PRODUCT_UNAVAILABLE],
            'image' => ['image'],
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        /**
         * Can fix data if needed, like:
         *
         * $data['status'] = Product::PRODUCT_UNAVAILABLE
         *
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

        if ($request->missing('status'))
        {
            $data['status'] = Product::PRODUCT_UNAVAILABLE;
        }

        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        //
    }
}
