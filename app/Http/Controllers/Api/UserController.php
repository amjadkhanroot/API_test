<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request) {
        $user = auth() -> user();

        if ( !Hash::check($request -> password, $user -> password)) {
            return response() -> json(['message', 'current password incorrect!'], 401);
        }

        $validateDate = $request -> validate([
            'password' => 'required',
            'newPassword' => 'required|confirmed',
            'newPassword_confirmation' => 'required'
        ]);

        $user -> password = bcrypt($validateDate['newPassword']);

        if ( $user -> save()) {
            return ['message' => 'password update successfully!'];
        }else{
            return response() -> json(['message' => 'something went wrong, please try again!'], 500);
        }
    }

    public function updateProfile(Request $request) {

        $validateData = $request -> validate([
            'name' => 'required',
            'email' => 'required|unique:users,email, '.auth() ->id()
        ]);

        if ( auth() -> user() -> update($validateData)) {
            return ['message' => 'profile updated successfully!'];
        }

        return response() -> json(['message' => 'something went wrong, please try again!'], 500);
    }
}
