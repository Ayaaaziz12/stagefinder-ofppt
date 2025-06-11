<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Verify company authentication
            $company = Auth::guard('company')->user();
            if (!$company) {
                return response()->json(['error' => 'Unauthorized. Company authentication required.'], 401);
            }

            $query = Student::query();

            // Search by name
            if ($request->has('name')) {
                $query->where(function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }

            // Search by skills
            if ($request->has('skills')) {
                $skills = is_array($request->skills) ? $request->skills : [$request->skills];
                $query->where(function ($q) use ($skills) {
                    foreach ($skills as $skill) {
                        $q->where('skills', 'like', '%' . $skill . '%');
                    }
                });
            }

            // Get the results
            $profiles = $query->select([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'country',
                'ville',
                'sex',
                'skills',
                'profile_picture'
            ])->get();

            return response()->json([
                'message' => 'Profiles retrieved successfully',
                'profiles' => $profiles
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function random()
    {
        try {
            // Verify company authentication
            $company = Auth::guard('company')->user();
            if (!$company) {
                return response()->json(['error' => 'Unauthorized. Company authentication required.'], 401);
            }

            // Get 50 random students
            $profiles = Student::select([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'country',
                'ville',
                'sex',
                'skills',
                'profile_picture'
            ])
                ->inRandomOrder()
                ->limit(50)
                ->get();

            return response()->json([
                'message' => 'Random profiles retrieved successfully',
                'profiles' => $profiles
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
