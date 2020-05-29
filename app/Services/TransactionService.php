<?php

namespace App\Services;


use App\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TransactionService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function makeTransaction(Request $request)
    {
        if ($this->isValidValue($request->value)) {
            return $this->store($request->all());
        }

        return response()->json('Transação Não Autorizada', 401);
    }

    private function isValidValue($value)
    {
        try {
            $response = $this->client->post('users-api-php/transactions/authorize', ['json' => ['value' => $value]]);
        } catch (\Exception $e) {
            return false;
        }

        return ($response->getStatusCode() === 200);
    }

    private function store($data)
    {
        $data['transaction_date'] = Carbon::now();
        return Transaction::create($data);
    }
}
