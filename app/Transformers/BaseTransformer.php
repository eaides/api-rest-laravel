<?php
/**
 * Created by PhpStorm.
 * User: ernesto
 * Date: 1/15/20
 * Time: 12:39 PM
 */

namespace App\Transformers;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    protected abstract static function getOriginalAttributes($reverse=false);

    public static function originalAttribute($index)
    {
        $attributes = static::getOriginalAttributes();

        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = static::getOriginalAttributes(true);

        return array_key_exists($index, $attributes) ?
            $attributes[$index] : null;
    }

}