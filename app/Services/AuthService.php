<?php

namespace App\Services;

use App\Models\Student;
use App\Models\RefreshToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Register a new student.
     *
     * @param array $data
     * @return array [student, token]
     */
    public function registerStudent(array $data)
    {
        $student = Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
            'status' => 'active',
        ]);

        $token = Auth::guard('api-student')->login($student);

        return [
            'student' => $student,
            'token' => $token,
        ];
    }

    /**
     * Login student and return token.
     *
     * @param string $email
     * @param string $password
     * @return array|null [student, token]
     */
    public function loginStudent($email, $password)
    {
        $credentials = ['email' => $email, 'password' => $password];

        if (!$token = Auth::guard('api-student')->attempt($credentials)) {
            return null;
        }

        $student = Auth::guard('api-student')->user();

        return [
            'student' => $student,
            'token' => $token,
        ];
    }

    /**
     * Refresh student token.
     *
     * @return string
     */
    public function refreshToken()
    {
        return Auth::guard('api-student')->refresh();
    }

    /**
     * Logout student.
     *
     * @return void
     */
    public function logoutStudent()
    {
        Auth::guard('api-student')->logout();
    }
}
