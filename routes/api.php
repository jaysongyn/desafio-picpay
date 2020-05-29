<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['prefix' => 'users'], function () use ($router) {
    $router->post('', 'UserController@store');
    $router->get('', 'UserController@index');
    $router->get('/{user_id}', 'UserController@show');

    $router->post('/consumers', 'ConsumerController@store');
    $router->post('/sellers', 'SellerController@store');
});
$router->group(['prefix' => 'transactions'], function () use ($router) {
    $router->get('/{transaction_id}', 'TransactionController@show');
    $router->post('', 'TransactionController@store');
    $router->post('/authorize', 'TransactionController@authorizeTransaction');
});
