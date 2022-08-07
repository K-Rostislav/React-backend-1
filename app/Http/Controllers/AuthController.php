<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {

        $data = $request->validate([
            'name' => ['required', 'string'],
            'surename' => ['required', 'string'],
            'patronymic' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string'],
        ]);

        if($data) {
            $user = new User;
            $user->name = $data['name'];
            $user->surename = $data['surename'];
            $user->patronymic = $data['patronymic'];
            $user->phone = $data['phone'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();
            
            return response()->json([
                'token' => $user->createToken('TOKEN')->plainTextToken
            ]);
        }

        return response()->json(['status' => 'error'], 500);

    }

    public function login(Request $request) {

        $data = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required'],
        ]);


        if(auth('web')->attempt($data)) {

            $user = User::where('phone', $request->phone)->first();

            return response()->json([
                'token' => $user->createToken('TOKEN')->plainTextToken
            ]);

        }

        return response()->json(['status' => 'error'], 500);
    }
}
