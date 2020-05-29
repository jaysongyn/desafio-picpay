<?php

namespace Tests;

use App\Consumer;
use App\Seller;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ConsumerTest extends TestCase
{
    use DatabaseMigrations;


    public function testCreateConsumer()
    {
        $newConsumer = factory(Consumer::class)->raw();

        $response = $this->post('/users/consumers', $newConsumer);
        unset($newConsumer['account_id']);

        $response
            ->seeInDatabase('consumers', $newConsumer)
            ->assertResponseStatus(200);
    }

    public function testCreateConsumerValidateBeforeCreate()
    {
        $response = $this->post('/users/consumers', []);

        $response->assertResponseStatus(422);
    }

    public function testCreateConsumerUserCanOnlyHaveOneConsumerAccount()
    {
        $consumer = factory(Consumer::class)->create();
        $newConsumer = factory(Consumer::class)->raw(['user_id' => $consumer->user_id]);

        $response = $this->post('/users/consumers', $newConsumer);
        $response
            ->seeJsonEquals(['user_id' => ['O campo user id já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

    public function testCreateConsumerUsernameMustBeUniqueInConsumersAndSellersAccount()
    {
        factory(Seller::class)->create(['username' => 'uniqueUsername']);
        $newConsumer = factory(Consumer::class)->raw(['username' => 'uniqueUsername']);

        $response = $this->post('/users/consumers', $newConsumer);
        $response
            ->seeJsonEquals(['username' => ['O campo username já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

    public function testCreateConsumerUsernameMustBeUniqueInConsumersAccount()
    {
        factory(Consumer::class)->create(['username' => 'uniqueUsername']);
        $newConsumer = factory(Consumer::class)->raw(['username' => 'uniqueUsername']);

        $response = $this->post('/users/consumers', $newConsumer);
        $response
            ->seeJsonEquals(['username' => ['O campo username já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }



}
