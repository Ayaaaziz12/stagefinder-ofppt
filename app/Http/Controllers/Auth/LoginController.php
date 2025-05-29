<?php

namespace App\Http\Controllers\Auth;

use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class LoginController extends Controller
{
    // use Authenticatable, Notifiable;

    public function login(Request $request)
    {
        try {
            $request->validate([
                'student_checkbox' => 'sometimes|boolean',
                'company_checkbox' => 'sometimes|boolean',
                'email_student' => 'required_if:student_checkbox,true|email',
                'password_student' => 'required_if:student_checkbox,true|string',
                'email_company' => 'required_if:company_checkbox,true|email',
                'password_company' => 'required_if:company_checkbox,true|string',
                'rc' => 'required_if:company_checkbox,true|string'
            ]);

            // Validate checkbox exclusivity
            if ($request->student_checkbox && $request->company_checkbox) {
                return response()->json([
                    'error' => 'Cannot select both student and company login.'
                ], 422);
            }

            // Student Login
            if ($request->student_checkbox) {
                $credentials = [
                    'email' => $request->email_student,
                    'password' => $request->password_student
                ];

                if (!$token = auth('student')->attempt($credentials)) {
                    return response()->json([
                        'error' => 'Invalid student credentials.'
                    ], 401);
                }

                $user = auth('student')->user();
                return $this->respondWithToken($token, 'student', $user);
            }

            // Company Login
            if ($request->company_checkbox) {
                $credentials = [
                    'rc' => $request->rc,
                    'password' => $request->password_company
                ];

                if (!$token = auth('company')->attempt($credentials)) {
                    return response()->json([
                        'error' => 'Invalid company credentials.'
                    ], 401);
                }

                $user = auth('company')->user();
                return $this->respondWithToken($token, 'company', $user);
            }

            return response()->json([
                'error' => 'Please select student or company login.'
            ], 422);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login.'
            ], 500);
        }
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
