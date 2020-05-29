<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Account;
use App\Consumer;
use Faker\Generator as Faker;

$factory->define(Consumer::class, function (Faker $faker) {
    return [
        'username' => $faker->text(11),
        'user_id' => factory(User::class)->create()->id,
        'account_id' => factory(Account::class)->create()->id,
    ];
});
