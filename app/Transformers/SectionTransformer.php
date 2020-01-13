<?php

namespace App\Transformers;

use App\Section;
use League\Fractal\TransformerAbstract;

class SectionTransformer extends TransformerAbstract
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

        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'        => 'id',

            'title'             => 'name',

            'creationDate'      => 'created_at',
            'updatedDate'       => 'updated_at',
            'deletedDate'       => 'deleted_at',
        ];
        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }
}
