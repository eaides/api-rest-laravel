<?php

namespace App\Transformers;

use App\Observers\ProductObserver;
use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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

        ];
    }

    public static function originalAttribute($index)
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

        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }
}
