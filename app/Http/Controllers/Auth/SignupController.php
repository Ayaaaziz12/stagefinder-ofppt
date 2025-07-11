<?php

namespace App\Http\Controllers\Auth;

use App\Models\Student;
use App\Models\Company;
use App\Models\Rc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class SignupController extends Controller
{
    public function register(Request $request)
    {
        // Debug information
        Log::info('Signup request received', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            $request->validate([
                'student_checkbox' => 'sometimes|boolean',
                'company_checkbox' => 'sometimes|boolean',

                // Student fields
                'first_name' => 'required_if:student_checkbox,true|string',
                'last_name' => 'required_if:student_checkbox,true|string',
                'email_student' => [
                    'required_if:student_checkbox,true',
                    'email',
                    'unique:students,email',
                    function ($attribute, $value, $fail) {
                        if (Company::where('email', $value)->exists()) {
                            $fail('This email is already registered as a company. Please use a different email.');
                        }
                    }
                ],
                'password_student' => 'required_if:student_checkbox,true|string|min:6',
                'phone' => 'required_if:student_checkbox,true|string|unique:students,phone',

                // Company fields
                'name' => 'required_if:company_checkbox,true|string',
                'rc' => 'required_if:company_checkbox,true|string',
                'company_password' => 'required_if:company_checkbox,true|string|confirmed',
                'email' => [
                    'required_if:company_checkbox,true',
                    'email',
                    'unique:companies,email',
                    function ($attribute, $value, $fail) {
                        if (Student::where('email', $value)->exists()) {
                            $fail('This email is already registered as a student. Please use a different email.');
                        }
                    }
                ],
                'domain' => 'required_if:company_checkbox,true|string',
                'address' => 'required_if:company_checkbox,true|string',
                'country' => 'required_if:company_checkbox,true|string',
                'ville' => 'required_if:company_checkbox,true|string',
                'date' => 'required_if:company_checkbox,true|date'
            ], [
                'email_student.unique' => 'This email address is already registered. Please use a different email or try logging in.',
                'email_student.required_if' => 'Email is required for student registration.',
                'email_student.email' => 'Please provide a valid email address.',
                'phone.required_if' => 'Phone number is required for student registration.',
                'phone.string' => 'Please provide a valid phone number.',
                'phone.unique' => 'This phone number is already registered. Please use a different phone number.',
                'password_student.required_if' => 'Password is required for student registration.',
                'password_student.min' => 'Password must be at least 6 characters long.',
                'first_name.required_if' => 'First name is required for student registration.',
                'last_name.required_if' => 'Last name is required for student registration.'
            ]);

            // Validate checkbox exclusivity
            if ($request->student_checkbox && $request->company_checkbox) {
                return response()->json([
                    'message' => 'Cannot select both student and company registration.',
                ], 422);
            }

            // Register Student
            if ($request->student_checkbox) {
                $student = Student::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email_student,
                    'password' => Hash::make($request->password_student),
                    'phone' => $request->phone,
                ]);

                return response()->json([
                    'message' => 'Student registered successfully',
                    'student' => $student,
                ]);
            }

            // Register Company
            if ($request->company_checkbox) {
                Log::info('Creating company with RC', ['rc_value' => $request->rc]);

                // Create the company with RC as a string
                $company = Company::create([
                    'name' => $request->name,
                    'rc' => $request->rc,
                    'password' => Hash::make($request->company_password),
                    'email' => $request->email,
                    'domain' => $request->domain,
                    'address' => $request->address,
                    'country' => $request->country,
                    'ville' => $request->ville,
                    'date' => $request->date,
                    'is_verified' => false
                ]);

                Log::info('Company created successfully', ['company_id' => $company->id]);

                return response()->json([
                    'message' => 'Company registered successfully',
                    'company' => $company,
                ]);
            }

            return response()->json([
                'message' => 'Please select student or company registration.',
            ], 422);
        } catch (ValidationException $e) {
            Log::error('Validation error in registration', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in registration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred during registration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
