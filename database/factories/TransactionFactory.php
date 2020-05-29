<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use App\Account;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'value' => $faker->randomFloat(2, 0, 99),
        'transaction_date' => Carbon::now(),
        'payee_id' => factory(Account::class)->create()->id,
        'payer_id' => factory(Account::class)->create()->id,
    ];
});
