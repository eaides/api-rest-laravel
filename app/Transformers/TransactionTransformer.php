<?php

namespace App\Transformers;

use App\Transaction;

class TransactionTransformer extends BaseTransformer
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        // meanwhile product_id and buyer_id    @todo
        // will be returned as integer => use foreign key
        return [
            'identifier'        => (int)$transaction->id,

            'quantity'          => (int)$transaction->quantity,
            'buyer'             => (int)$transaction->buyer_id,
            'product'           => (int)$transaction->product_id,

            'creationDate'      => (string)$transaction->created_at,
            'updatedDate'       => (string)$transaction->updated_at,
            'deletedDate'       => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,

            [
                'rel' => 'self',
                'href' => route('transactions.show', $transaction->id),
            ],
            [
                'rel' => 'transaction.categories',
                'href' => route('transactions.categories.index', $transaction->id),
            ],
            [
                'rel' => 'transaction.sellers',
                'href' => route('transactions.sellers.index', $transaction->id),
            ],
            [
                'rel' => 'buyer',
                'href' => route('buyers.show', $transaction->buyer_id),
            ],
            [
                'rel' => 'product',
                'href' => route('products.show', $transaction->product_id),
            ],
        ];
    }

    protected static function getOriginalAttributes($reverse=false)
    {
        $attributes = [
            'identifier'        => 'id',

            'quantity'          => 'quantity',
            'buyer'             => 'buyer_id',
            'product'           => 'product_id',

            'creationDate'      => 'created_at',
            'updatedDate'       => 'updated_at',
            'deletedDate'       => 'deleted_at',
        ];

        if ($reverse)
        {
            $attributes = array_flip($attributes);
        }

        return $attributes;
    }
}

