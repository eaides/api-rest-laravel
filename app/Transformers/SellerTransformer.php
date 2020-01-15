<?php

namespace App\Transformers;

use App\Seller;

class SellerTransformer extends BaseTransformer
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
    public function transform(Seller $seller)
    {
        return [
            'identifier'        => (int)$seller->id,

            'first_name'        => (string)$seller->name,
            'last_name'         => (string)$seller->surname,
            'e-mail'            => (string)$seller->email,
            'biography'         => (string)$seller->bio,
            'image'             => isset($seller->image) ? (string)$seller->image : null,

            'creationDate'      => (string)$seller->created_at,
            'updatedDate'       => (string)$seller->updated_at,
            'deletedDate'       => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id),
                ],
                [
                    'rel' => 'seller.buyers',
                    'href' => route('sellers.buyers.index', $seller->id),
                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id),
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel' => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id),
                ],
            ],
        ];
    }

    protected static function getOriginalAttributes($reverse=false)
    {
        $attributes = [
            'identifier'        => 'id',

            'first_name'        => 'name',
            'last_name'         => 'surname',
            'e-mail'            => 'email',
            'biography'         => 'bio',
            'image'             => 'image',

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
