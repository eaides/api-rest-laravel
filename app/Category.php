<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the posts for the user
     */
    public function posts() {
        $this->hasMany(Post::class);
    }

    /**
     * Mutators
     */

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Accessors
     */

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

}
