<?php

namespace App;

class Seller extends User
{
    public function products()
    {
        $this->hasMany(Product::class);
    }
}
