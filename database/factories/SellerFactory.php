<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Account;
use App\Seller;
use Faker\Generator as Faker;

$factory->define(Seller::class, function (Faker $faker) {
    return [
        'cnpj' => $faker->text(14),
        'fantasy_name' => $faker->company,
        'social_name' => $faker->company,
        'username' => $faker->text(11),
        'user_id' => factory(User::class)->create()->id,
        'account_id' => factory(Account::class)->create()->id,
    ];
});
