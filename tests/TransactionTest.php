<?php

namespace Tests;

use App\Account;
use App\Transaction;
use Laravel\Lumen\Testing\DatabaseMigrations;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateTransaction()
    {
        $newTransaction = factory(Transaction::class)->raw();

        $response = $this->post('/transactions', $newTransaction);

        $response
            ->seeInDatabase('transactions', $newTransaction)
            ->assertResponseStatus(200);
    }

    public function testCreateTransactionValidateBeforeCreate()
    {
        $response = $this->post('/transactions', []);

        $response
            ->assertResponseStatus(422);
    }

    public function testCreateTransactionAccountIdMustBeDifferentForPayeeAndPayer()
    {
        $account =  factory(Account::class)->create();
        $newTransaction = factory(Transaction::class)->raw(['payee_id' => $account->id, 'payer_id' => $account->id]);
        $response = $this->post('/transactions', $newTransaction);

        $response
            ->seeJsonEquals(['payee_id' => ['Os campos payee id e payer id devem ser diferentes.']])
            ->assertResponseStatus(422);
    }

    public function testCreateTransactionUnauthorizedForValueEqualsOrGreaterOneHundred()
    {
        $newTransaction = factory(Transaction::class)->raw(['value' => 100]);

        $response = $this->post('/transactions', $newTransaction);

        $response
            ->assertResponseStatus(401);
    }

    public function testShowTransaction()
    {
        $transaction =  factory(Transaction::class)->create();
        $response = $this->get('/transactions/' . $transaction->id);
        $response
            ->seeJsonEquals($transaction->toArray())
            ->assertResponseStatus(200);
    }

    public function testShowUserReturnNotFoundWhenDoesntExists()
    {
        $response = $this->get('/transactions/1');
        $response->assertResponseStatus(404);
    }
}
