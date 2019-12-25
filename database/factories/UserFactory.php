<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Helpers\Helper;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $password;

    $path = Helper::getGetPublicSubFolder('img');
    $images = Helper::getFileNamesInFolder($path, false, false, true);
    $images[] = null;

    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'role' => $faker->randomElement([User::ROLE_READER, User::ROLE_PUBLISHER]),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $email_verified_at = $faker->randomElement([now(), null]),
        'password' => $password ?: bcrypt('secret'),
        'bio' => $faker->text,
        'image' => $faker->randomElement($images),
        'remember_token' => $faker->randomElement([Str::random(10), null]),
        'verification_token' => (is_null($email_verified_at)) ? User::generateVerificationToken() : null,
    ];
});
