<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'buyer_id', 'product_id',
    ];

    protected function product()
    {
        $this->belongsTo(Product::class);
    }

    protected function buyer()
    {
        $this->belongsTo(Buyer::class);
    }

}
