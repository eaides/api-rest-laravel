<?php

namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
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
    public function transform(Post $post)
    {
        // meanwhile user_id and section_id    @todo
        // will be returned as integer => use foreign key
        return [
            'identifier'        => (int)$post->id,

            'title'             => (string)$post->title,
            'body'              => (string)$post->content,
            'image'             => isset($post->image) ?
                (string)url("img/{$post->image}") :
                null,
            'user'              => (int)$post->user_id,
            'section'           => (int)$post->section_id,

            'creationDate'      => (string)$post->created_at,
            'updatedDate'       => (string)$post->updated_at,
            'deletedDate'       => isset($post->deleted_at) ? (string)$post->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'        => 'id',

            'title'             => 'title',
            'body'              => 'content',
            'image'             => 'image',
            'user'              => 'user_id',
            'section'           => 'section_id',

            'creationDate'      => 'created_at',
            'updatedDate'       => 'updated_at',
            'deletedDate'       => 'deleted_at',
        ];
        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }
}
