<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class SellerTransactionController extends ApiController
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
        /**
         * Debug sql query
         *
         *    \DB::listen(function($sql) {
         *        var_dump($sql);
         *    });
         */

        $transactions = $seller->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->unique('id')
            ->values();

        return $this->showAll($transactions);
    }

}
