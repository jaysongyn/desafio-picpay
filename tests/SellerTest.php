<?php

namespace Tests;

use App\Seller;
use Laravel\Lumen\Testing\DatabaseMigrations;

class SellerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateSeller()
    {
        $newSeller = factory(Seller::class)->raw();

        $response = $this->post('/users/sellers', $newSeller);
        unset($newSeller['account_id']);

        $response
            ->seeInDatabase('sellers', $newSeller)
            ->assertResponseStatus(200);
    }

    public function testCreateSellerValidateBeforeCreate()
    {
        $response = $this->post('/users/sellers', []);

        $response->assertResponseStatus(422);
    }

    public function testCreateSellerUserCanOnlyHaveOneSellerAccount()
    {
        $seller = factory(Seller::class)->create();
        $newSeller = factory(Seller::class)->raw(['user_id' => $seller->user_id]);

        $response = $this->post('/users/sellers', $newSeller);
        $response
            ->seeJsonEquals(['user_id' => ['O campo user id já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

    public function testCreateSellerUsernameMustBeUniqueInSellersAndConsumersAccount()
    {
        factory(Seller::class)->create(['username' => 'uniqueUsername']);
        $newSeller = factory(Seller::class)->raw(['username' => 'uniqueUsername']);

        $response = $this->post('/users/sellers', $newSeller);
        $response
            ->seeJsonEquals(['username' => ['O campo username já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

    public function testCreateSellerUsernameMustBeUniqueInSellersAccount()
    {
        factory(Seller::class)->create(['username' => 'uniqueUsername']);
        $newSeller = factory(Seller::class)->raw(['username' => 'uniqueUsername']);

        $response = $this->post('/users/sellers', $newSeller);
        $response
            ->seeJsonEquals(['username' => ['O campo username já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

}
