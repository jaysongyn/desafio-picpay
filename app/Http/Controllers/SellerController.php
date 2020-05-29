<?php

namespace App\Http\Controllers;

use App\Account;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{

    private $rules = [
        'cnpj' => 'required|unique:sellers|max:14',
        'fantasy_name' => 'required|max:255',
        'social_name' => 'required|max:255',
        'username' => 'required|unique:consumers|unique:sellers',
        'user_id' => 'required|exists:users,id|unique:sellers'
    ];

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        $account = Account::create();
        return Seller::create(array_merge($request->all(), ['account_id' => $account->id]));
    }
}
