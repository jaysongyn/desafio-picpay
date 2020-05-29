<?php

namespace Tests;

use App\Consumer;
use App\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testListUser()
    {
        $user = factory(User::class, 2)->create();

        $response = $this->get('/users');

        $response
            ->seeJsonEquals($user->toArray())
            ->assertResponseStatus(200);
    }

    public function testListUserFilterByFullName()
    {
        $correctUser = factory(User::class)->create(['full_name' => 'correctUser']);
        factory(User::class)->create(['full_name' => 'wrongUser']);
        $response = $this->get('/users?q=correctUser');

        $response
            ->seeJsonEquals([$correctUser->toArray()])
            ->assertResponseStatus(200);
    }
    public function testListUserFilterByUsername()
    {
        $user = factory(User::class)->create(['full_name' => 'willFindByUserName']);
        factory(Consumer::class)->create(['username' => 'correctUser', 'user_id' => $user->id]);
        factory(Consumer::class)->create(['username' => 'wrongUser']);

        $response = $this->get('/users?q=correctUser');

        $response
            ->seeJsonEquals([$user->toArray()])
            ->assertResponseStatus(200);
    }


    public function testListUserFilterReturnNoUsersWhenNotFound()
    {
        factory(User::class)->create(['full_name' => 'correctUser']);

        $response = $this->get('/users?q=wrongUser');

        $response
            ->seeJsonEquals([]);
    }

    public function testShowUser()
    {
        $user = factory(User::class)->create();
        $user->load(['consumer', 'seller']);

        $response = $this->get('/users/' . $user->id);

        $response
            ->seeJsonEquals([
                'accounts' => [
                    'consumer' => $user->consumer,
                    'seller' => $user->seller
                ],
                'user' => $user
            ])
            ->assertResponseStatus(200);
    }

    public function testShowUserReturnNotFoundWhenDoesntExists()
    {
        $response = $this->get('/users/1');
        $response->assertResponseStatus(404);
    }

    public function testCreateUser()
    {
        $newUser =  factory(User::class)->raw();

        $response = $this->post('/users', $newUser);

        $response
            ->seeInDatabase('users', $newUser)
            ->assertResponseStatus(200);
    }

    public function testCreateUserValidateUserBeforeCreate()
    {
        $response = $this->post('/users', []);
        $response->assertResponseStatus(422);
    }

    public function testCreateUserCpfMustBeUnique()
    {
        factory(User::class)->create(['cpf' => '12345678910']);
        $newUser = factory(User::class)->raw(['cpf' => '12345678910']);

        $response = $this->post('/users', $newUser);

        $response
            ->seeJsonEquals(['cpf' => ['O campo cpf já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

    public function testCreateUserEmailMustBeUnique()
    {
        factory(User::class)->create(['email' => 'email@email.com']);
        $newUser = factory(User::class)->raw(['email' => 'email@email.com']);

        $response = $this->post('/users', $newUser);

        $response
            ->seeJsonEquals(['email' => ['O campo email já está sendo utilizado.']])
            ->assertResponseStatus(422);
    }

}
