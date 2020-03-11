<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')
            ->only('show');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $seller = Seller::has('products')->get();

        return $this->showAll($seller);
    }

    /**
     * Display the specified resource.
     *
     * by default here can not use implicit model injection
     * because need to check if the Seller (=User) has products
     * Can resolve by using global scopes for the Model Seller
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Seller $seller)
    {
        // deprecated
        // $seller = Seller::has('products')->findOrFail($id);

        return $this->showOne($seller);
    }

}
