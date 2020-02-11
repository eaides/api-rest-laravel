<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Transaction $transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Transaction $transaction)
    {
        $sellers = $transaction->product->seller;

        return $this->showOne($sellers);
    }

}
