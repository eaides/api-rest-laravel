<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => ['required','integer','min:1'],
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id)
        {
            return $this->errorResponse('The buyer must be different tham the seller',409);
        }

        if (!$buyer->isVerified())
        {
            return $this->errorResponse('The buyer must be verified',409);
        }

        if (!$product->seller->isVerified())
        {
            return $this->errorResponse('The seller must be verified',409);
        }

        if (!$product->isAvailable())
        {
            return $this->errorResponse('The product is not available',409);
        }

        if ($product->quantity < $request->quantity)
        {
            return $this->errorResponse('The product has not available quantities for the require',409);
        }

        $transaction = null;
        DB::transaction(function() use ($request, $product, $buyer, &$transaction)
        {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
        });

        if ($transaction instanceof Transaction)
        {
            return $this->showOne($transaction, 201);
        }

        return $this->errorResponse('fail to create transaction', 500);
    }

}
