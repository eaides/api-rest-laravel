<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            'identifier'        => (int)$user->id,

            'first_name'        => (string)$user->name,
            'last_name'         => (string)$user->surname,
            'e-mail'            => (string)$user->surname,
            'biography'         => (string)$user->bio,
            'image'             => isset($user->image) ?
                (string)url("img/{$user->image}") :
                null,
            'isVerified'        => (bool)$user->isVerified(),
            'isAdmin'           => (bool)$user->isAdmin(),
            'isPublisher'       => (bool)$user->isPublisher(),

            'creationDate'      => (string)$user->created_at,
            'updatedDate'       => (string)$user->updated_at,
            'deletedDate'       => isset($user->deleted_at) ? (string)$user->deleted_at : null,
        ];
    }
}
