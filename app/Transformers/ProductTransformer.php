<?php

namespace App\Transformers;

use App\Product;

class ProductTransformer extends BaseTransformer
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
    public function transform(Product $product)
    {
        // meanwhile seller_id      @todo
        // will be returned as integer => use foreign key
        return [
            'identifier'        => (int)$product->id,

            'title'             => (string)$product->name,
            'details'           => (string)$product->description,
            'available'         => (int)$product->quantity,
            'status'            => (string)$product->status,

            'image'             => isset($product->image) ?
                (string)url("img/{$product->image}") :
                null,
            'seller'            => (int)$product->seller_id,

            'creationDate'      => (string)$product->created_at,
            'updatedDate'       => (string)$product->updated_at,
            'deletedDate'       => isset($product->deleted_at) ? (string)$product->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
            ],
        ];
    }

    protected static function getOriginalAttributes($reverse=false)
    {
        $attributes = [
            'identifier'        => 'id',

            'title'             => 'name',
            'details'           => 'description',
            'available'         => 'quantity',
            'status'            => 'status',
            'image'             => 'image',
            'seller'            => 'seller_id',

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
