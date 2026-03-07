<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseApiController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api-admin')->attempt($credentials)) {
            return $this->response(false, 'Invalid credentials', [], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::guard('api-admin')->logout();
        return $this->response(true, 'Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api-admin')->refresh());
    }

    public function me()
    {
        return $this->response(true, 'Admin profile fetched', Auth::guard('api-admin')->user());
    }

    protected function respondWithToken($token)
    {
        return $this->response(true, 'Login successful', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api-admin')->factory()->getTTL() * 60,
            'user' => Auth::guard('api-admin')->user()
        ]);
    }
}
