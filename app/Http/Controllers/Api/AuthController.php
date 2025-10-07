<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token'=> $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $creds = $request->validated();

        if (!Auth::attempt($creds)) {
            return response()->json(['success'=>false,'message'=>'Invalid credentials'], 401);
        }

        $user = User::where('email',$creds['email'])->first();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success'=>true,
            'data'=>[
                'user'=>$user,
                'token'=>$token,
                'token_type'=>'Bearer'
            ]
        ], 200);
    }

    public function me(): JsonResponse
    {
        return response()->json(['success'=>true,'data'=>Auth::user()]);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['success'=>true,'message'=>'Logged out'], 200);
    }
}
