<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {
        return [
            'identifier'        => (int)$buyer->id,

            'first_name'        => (string)$buyer->name,
            'last_name'         => (string)$buyer->surname,
            'e-mail'            => (string)$buyer->email,
            'biography'         => (string)$buyer->bio,
            'image'             => isset($buyer->image) ? (string)$buyer->image : null,

            'creationDate'      => (string)$buyer->created_at,
            'updatedDate'       => (string)$buyer->updated_at,
            'deletedDate'       => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
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
