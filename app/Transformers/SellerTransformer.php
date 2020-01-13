<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
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
        ];
    }

    public static function originalAttribute($index)
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
        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }
}
