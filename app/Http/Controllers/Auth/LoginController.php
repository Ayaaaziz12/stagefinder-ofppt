<?php

namespace App\Http\Controllers\Auth;

use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'student_checkbox' => 'sometimes|boolean',
            'company_checkbox' => 'sometimes|boolean',
            'email' => 'required_if:student_checkbox,true|email',
            'rc' => 'required_if:company_checkbox,true|string',
            'password' => 'required|string',
        ]);

        // Validate checkbox exclusivity
        if ($request->student_checkbox && $request->company_checkbox) {
            throw ValidationException::withMessages([
                'error' => 'Cannot select both student and company login.',
            ]);
        }

        // Student Login
        if ($request->student_checkbox) {
            $credentials = $request->only('email', 'password');
            $guard = Auth::guard('student');

            if (!$token = $guard->attempt($credentials)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid student credentials.'],
                ]);
            }

            return $this->respondWithToken($token, 'student', $guard->user());
        }

        // Company Login
        if ($request->company_checkbox) {
            $credentials = $request->only('rc', 'password');
            $guard = Auth::guard('company');

            if (!$token = $guard->attempt($credentials)) {
                throw ValidationException::withMessages([
                    'rc' => ['Invalid company credentials.'],
                ]);
            }

            return $this->respondWithToken($token, 'company', $guard->user());
        }

        throw ValidationException::withMessages([
            'error' => 'Please select student or company login.',
        ]);
    }

    protected function respondWithToken($token, $userType, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user_type' => $userType,
            'user' => $user
        ]);
    }
}
