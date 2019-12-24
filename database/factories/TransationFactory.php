<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Seller;
use App\Transaction;
use App\User;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {

    // seller is one User that has product to sell
    $seller = Seller::has('products')->get()->random();

    // buyer can not be the same User (seller), I can not by a product of my own
    $buyer = User::all()->except($seller->id)->random();

    return [
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $buyer->id,
        'product_id' => $seller->products->random()->id,
    ];
});
