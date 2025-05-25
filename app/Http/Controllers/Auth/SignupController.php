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
                'email' => 'required_if:student_checkbox,true|email|unique:students,email',
                'password' => 'required_if:student_checkbox,true||string|confirmed',
                'phone' => 'required_if:student_checkbox,true|string',

                // Company fields
                'name' => 'required_if:company_checkbox,true|string|unique:companies,name',
                'rc' => 'required_if:company_checkbox,true|string',
                'company_password' => 'required_if:company_checkbox,true|string|confirmed',
                'email' => 'required_if:company_checkbox,true|email|unique:companies,email',
                'domain' => 'required_if:company_checkbox,true|string',
                'address' => 'required_if:company_checkbox,true|string',
                'country' => 'required_if:company_checkbox,true|string',
                'ville' => 'required_if:company_checkbox,true|string',
                'date' => 'required_if:company_checkbox,true|date'
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
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                ]);

                return response()->json([
                    'message' => 'Student registered successfully',
                    'student' => $student,
                ]);
            }

            // Register Company
            if ($request->company_checkbox) {
                Log::info('Creating RC record', ['rc_value' => $request->rc]);

                // First create the RC record
                $rc = Rc::create([
                    'value' => $request->rc
                ]);

                Log::info('RC record created', ['rc_id' => $rc->id]);

                // Then create the company with the RC ID
                $company = Company::create([
                    'name' => $request->name,
                    'id_rc' => $rc->id,
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
