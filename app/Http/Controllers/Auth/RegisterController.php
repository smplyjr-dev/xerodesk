<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserBio;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected function registered(Request $request, User $user)
    {
        return response()->json(['status' => [
            'A verification code has been sent to <strong>' . $user->email . '</strong>.',
            'Please verify your account as soon as possible.'
        ]]);
    }

    protected function validator(array $data)
    {
        $rules = [
            'first_name' => 'required|max:255|string',
            'last_name'  => 'required|max:255|string',
            'username'   => 'required|max:255|unique:users',
            'email'      => 'required|max:255|email',
            'password'   => 'required|min:8|confirmed'
        ];

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'status'   => 2
        ]);

        UserBio::create([
            'user_id'    => $user->id,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name']
        ]);

        return $user;
    }
}
