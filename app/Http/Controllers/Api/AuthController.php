<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {

         $validateData = $request -> validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $validateData['password'] = bcrypt($validateData['password']);
        $validateData['password_confirmation'] = bcrypt($validateData['password_confirmation']);

        $user = User::create($validateData);
        $accessToken = $user -> createToken('authToken') -> accessToken;

        return [$user, $accessToken];
    }

    public function login(Request $request) {

        $validateData = $request -> validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if( !auth() -> attempt($validateData)) {
            return response() -> json(['message' => 'invalid login!'], 401);
        }

        $accessToken = auth() -> user() ->createToken('authToken') -> accessToken;
        return ['user' => auth() -> user(), 'accessToken' => $accessToken];

    }
}
