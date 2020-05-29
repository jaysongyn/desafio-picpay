<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $rules = [
        'cpf' => 'required|string|max:11|unique:users',
        'email' => 'required|string|max:255|email|unique:users',
        'full_name' => 'required|string|max:255',
        'password' => 'required|string|max:255',
        'phone_number' => 'required|string|max:255'
    ];

    private $route = '/users';

    public function index(Request $request)
    {
        $stringSearch = $request->get('q');
        if (isset($stringSearch) and $stringSearch !== $this->route) {
            return  User::where('full_name', 'like', $stringSearch. '%')
               ->orWhereHas('consumer', function ($query) use ($stringSearch) {
                   $query->where('username', 'like', $stringSearch . '%');
               })
               ->orWhereHas('seller', function ($query) use ($stringSearch) {
                    $query->where('username', 'like', $stringSearch . '%');
               })
               ->get();
        }
        return User::orderBy('full_name', 'ASC')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);
        return User::create($request->all());
    }

    public function show(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        return [
            'accounts' => [
                'consumer' => $user->consumer,
                'seller' => $user->seller
            ],
            'user' => $user
        ];
    }
}
