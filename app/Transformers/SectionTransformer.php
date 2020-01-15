<?php

namespace App\Transformers;

use App\Section;

class SectionTransformer extends BaseTransformer
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
    public function transform(Section $section)
    {
        return [
            'identifier'        => (int)$section->id,

            'title'             => (string)$section->name,

            'creationDate'      => (string)$section->created_at,
            'updatedDate'       => (string)$section->updated_at,
            'deletedDate'       => isset($section->deleted_at) ? (string)$section->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sections.show', $section->id),
                ],
                [
                    'rel' => 'section.posts',
                    'href' => route('sections.posts.index', $section->id),
                ],
            ],
        ];
    }

    protected static function getOriginalAttributes($reverse=false)
    {
        $attributes = [
            'identifier'        => 'id',

            'title'             => 'name',

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
