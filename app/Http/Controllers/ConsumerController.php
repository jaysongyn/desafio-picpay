<?php

namespace App\Http\Controllers;

use App\Account;
use App\Consumer;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    private $rules = [
        'username' => 'required|unique:consumers|unique:sellers',
        'user_id' => 'required|exists:users,id|unique:consumers'
    ];

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $account = Account::create();
        return Consumer::create(array_merge($request->all(), ['account_id' => $account->id]));
    }
}
