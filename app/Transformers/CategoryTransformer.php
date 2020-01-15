<?php

namespace App\Transformers;

use App\Category;

class CategoryTransformer extends BaseTransformer
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
    public function transform(Category $category)
    {
        return [
            'identifier'        => (int)$category->id,

            'title'             => (string)$category->name,
            'details'           => (string)$category->description,

            'creationDate'      => (string)$category->created_at,
            'updatedDate'       => (string)$category->updated_at,
            'deletedDate'       => isset($category->deleted_at) ? (string)$category->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('categories.show', $category->id),
                ],
                [
                    'rel' => 'category.buyers',
                    'href' => route('categories.buyers.index', $category->id),
                ],
                [
                    'rel' => 'category.products',
                    'href' => route('categories.products.index', $category->id),
                ],
                [
                    'rel' => 'category.sellers',
                    'href' => route('categories.sellers.index', $category->id),
                ],
                [
                    'rel' => 'category.transactions',
                    'href' => route('categories.transactions.index', $category->id),
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
