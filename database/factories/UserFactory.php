<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'cpf' => $faker->text(11),
        'email' => $faker->unique()->companyEmail,
        'full_name' => $faker->name,
        'password' => $faker->password,
        'phone_number'=> $faker->phoneNumber,
    ];
});
