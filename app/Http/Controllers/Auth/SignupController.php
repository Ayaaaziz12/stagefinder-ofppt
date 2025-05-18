<?php

namespace App\Http\Controllers\Auth;

use App\Models\Student;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SignupController extends Controller
{
    public function register(Request $request)
    {
        // dd($request);
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
            'name' => 'required_if:company_checkbox,true|string',
            'rc' => 'required_if:company_checkbox,true|string|unique:companies,id_rc', // Replace `rc` with your company's RC column name
            'company_password' => 'required_if:company_checkbox,true|string|confirmed',
            'email' => 'required_if:company_checkbox,true|email|unique:companies,email',
            'domain' => 'required_if:company_checkbox,true|string'
        ]);


        // Validate checkbox exclusivity
        if ($request->student_checkbox && $request->company_checkbox) {
            throw ValidationException::withMessages([
                'error' => 'Cannot select both student and company registration.',
            ]);
        }

        // Register Student
        if ($request->student_checkbox) {
            $student = Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                // Add other student fields as needed
            ]);

            return response()->json([
                'message' => 'Student registered successfully',
                'student' => $student,
                // 'token' => $student->createToken('student-token')->plainTextToken,
            ]);
        }
        // Register Company
        if ($request->company_checkbox) {
            $company = Company::create([
                'name' => $request->name,
                'id_rc' => $request->rc,
                'password' => Hash::make($request->company_password),
                'email' => $request->email,
                'domain' => $request->domain,

                // Add other company fields as needed
            ]);

            return response()->json([
                'message' => 'Company registered successfully',
                'company' => $company,
                // 'token' => $company->createToken('company-token')->plainTextToken,
            ]);
        }

        throw ValidationException::withMessages([
            'error' => 'Please select student or company registration.',
        ]);
    }
}
