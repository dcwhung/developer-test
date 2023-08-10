<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        return $user;
    }

    public function login(LoginRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password))
            return response()->json(['message' => 'Credentials not match'], Response::HTTP_UNAUTHORIZED);

        $token = $user->createToken('api');
        return response()->json(['token' => $token->plainTextToken]);
    }

    public function forbidden()
    {
        return response()->json(['message' => 'You are not authenticated'], Response::HTTP_FORBIDDEN);
    }
}
