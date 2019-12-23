<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCT_AVAILABLE = 'available';
    const PRODUCT_UNAVAILABLE = 'unavailable';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'quantity', 'status', 'image',
        'seller_id',
    ];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->status)
        {
            $status = $this->status;
            if ($status != self::PRODUCT_AVAILABLE && $status != self::PRODUCT_UNAVAILABLE)
            {
                $this->status = self::PRODUCT_UNAVAILABLE;
            }
        }
        else
        {
            $this->status = self::PRODUCT_UNAVAILABLE;
        }
        return parent::save($options);
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return ($this->status == self::PRODUCT_AVAILABLE);
    }

    /**
     * @return bool
     */
    public function isUnavailable()
    {
        return (!$this->isAvailable());
    }

    public function categories()
    {
        $this->belongsToMany(Category::class);
    }

    public function seller()
    {
        $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        $this->hasMany(Transaction::class);
    }
}
