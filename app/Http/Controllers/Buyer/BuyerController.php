<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
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
        $buyers = Buyer::has('transactions')->get();

        return $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * by default here can not use implicit model injection
     * because need to check if the Buyer (=User) has transactions
     * Can resolve by using global scopes for the Model Buyer
     *
     * deprecated @param  int  $id
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Buyer $buyer)
    {
        // deprecated
        // $buyer = Buyer::has('transactions')->findOrFail($id);

        return $this->showOne($buyer);
    }

}
