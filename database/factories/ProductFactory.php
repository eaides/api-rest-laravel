<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\Helper;
use App\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    $path = Helper::getGetPublicSubFolder('img');
    $images = Helper::getFileNamesInFolder($path, false, false, true);
    $images[] = null;

    return [
        'name' => $faker->word . ' product',
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::PRODUCT_AVAILABLE, Product::PRODUCT_UNAVAILABLE]),
        'image' => $faker->randomElement($images),
        // 'seller_id' => User::inRandomOrder()->first()->id, OR as below
        'seller_id' => User::all()->random()->id,
    ];
});
