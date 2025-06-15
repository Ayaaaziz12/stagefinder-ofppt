<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function update(Request $request)
    {
        try {
            // Get authenticated student
            $student = Auth::guard('student')->user();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized. Student authentication required.'], 401);
            }

            // Validate request data
            $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $student->id,
                'phone' => 'sometimes|string|max:255',
                'country' => 'sometimes|string|max:255',
                'ville' => 'sometimes|string|max:255',
                'address' => 'sometimes|string|max:255',
                'date_of_birth' => 'sometimes|date',
                'sex' => 'sometimes|string|in:M,F',
                'skills' => 'sometimes|string',
                'profile_picture' => 'sometimes|nullable|url|max:255',
                'password' => 'sometimes|string|min:6|confirmed',
            ]);

            // Update student information
            $updateData = $request->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'country',
                'ville',
                'address',
                'date_of_birth',
                'sex',
                'skills',
                'profile_picture'
            ]);

            // If password is provided, hash it
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Update using query builder
            DB::table('students')
                ->where('id', $student->id)
                ->update($updateData);

            // Get updated student data
            $updatedStudent = Student::find($student->id);

            return response()->json([
                'message' => 'Student information updated successfully',
                'student' => $updatedStudent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update student information',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id = null)
    {
        try {
            // If no ID is provided, return the authenticated student's profile
            if ($id === null) {
                $student = Auth::guard('student')->user();
                if (!$student) {
                    return response()->json(['error' => 'Unauthorized. Student authentication required.'], 401);
                }
                return response()->json($student);
            }

            // If ID is provided, return that specific student's profile
            $student = Student::findOrFail($id);
            return response()->json($student);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch student information',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
