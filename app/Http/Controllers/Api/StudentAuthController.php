<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'mobile' => 'required|string|unique:students',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->authService->registerStudent($request->all());

        return response()->json([
            'message' => 'Student registered successfully',
            'student' => $result['student'],
            'token' => $result['token'],
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->authService->loginStudent($request->email, $request->password);

        if (!$result) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'student' => $result['student'],
            'token' => $result['token'],
        ]);
    }

    public function me()
    {
        return response()->json(auth()->guard('api-student')->user());
    }

    public function logout()
    {
        $this->authService->logoutStudent();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        $token = $this->authService->refreshToken();
        return response()->json(['token' => $token]);
    }
}
