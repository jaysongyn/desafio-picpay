<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $rules = [
        'payee_id' => 'required|exists:accounts,id|different:payer_id',
        'payer_id' => 'required|exists:accounts,id',
        'value' => 'required|numeric'
    ];

    public function store(Request $request, TransactionService $transactionService)
    {
        $this->validate($request, $this->rules);
        return $transactionService->makeTransaction($request);
    }

    public function show(Request $request, int $transactionId)
    {
        return Transaction::findOrFail($transactionId);
    }

    public function authorizeTransaction(Request $request)
    {
        if ($request->value < 100) {
            return response()->json();
        }

        return response()->json('transação não autorizada', 401);
    }
}
