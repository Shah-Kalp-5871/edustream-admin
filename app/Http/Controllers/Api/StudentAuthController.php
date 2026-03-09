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
            'mobile' => 'required|string|digits:10|unique:students',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Auto-generate email and password
        $email = $request->mobile . '_' . \Illuminate\Support\Str::random(4) . '@edustream.test';
        $password = \Illuminate\Support\Str::random(10);

        $student = \App\Models\Student::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'course_id' => $request->course_id,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'status' => 'active',
            'mobile_verified_at' => now(), // already verified if they reached here via OTP
        ]);

        $token = auth()->guard('api-student')->login($student);

        return response()->json([
            'message' => 'Student registered successfully',
            'student' => $student,
            'token' => $token,
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

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate 4 digit OTP
        $otp = (string) rand(1000, 9999);
        
        // Store in cache for 5 minutes
        \Illuminate\Support\Facades\Cache::put('otp_' . $request->mobile, $otp, now()->addMinutes(5));

        return response()->json([
            'message' => 'OTP sent successfully',
            'mobile' => $request->mobile,
            'otp' => $otp // Return OTP for testing
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string',
            'otp' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->mobile);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            // Also allow a static bypass OTP for review or testing
            if ($request->otp !== '1234') {
                return response()->json(['error' => 'Invalid or expired OTP'], 400);
            }
        }

        // Try to find the student
        $student = \App\Models\Student::where('mobile', $request->mobile)->first();

        // Delete OTP
        \Illuminate\Support\Facades\Cache::forget('otp_' . $request->mobile);

        if (!$student) {
            return response()->json([
                'status' => 'needs_signup',
                'mobile' => $request->mobile
            ]);
        }

        if (!$student->mobile_verified_at) {
            $student->mobile_verified_at = now();
            $student->save();
        }

        // Login using JWT
        $token = auth()->guard('api-student')->login($student);

        return response()->json([
            'status' => 'login',
            'message' => 'Login successful',
            'student' => $student,
            'token' => $token
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

    public function updateProfile(Request $request)
    {
        $student = auth()->guard('api-student')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:students,email,' . $student->id,
            'mobile' => 'sometimes|string|digits:10|unique:students,mobile,' . $student->id,
            'bio' => 'nullable|string',
            'password' => 'sometimes|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'mobile', 'bio']);
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $student->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'student' => $student
        ]);
    }
}
