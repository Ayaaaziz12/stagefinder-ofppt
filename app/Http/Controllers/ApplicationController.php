<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApplicationController extends Controller
{
    // Get all applications for current student
    public function index()
    {

        $student = Auth::guard('student')->user();
        return Auth::guard('student')->user()
            ->$student
            ->with(['offer.company', 'offer.jobtype'])
            ->get();
    }

    // Create new application
    public function store(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id'
        ]);

        $student = Auth::guard('student')->user();
        $offer = Offer::findOrFail($request->offer_id);

        // Check if already applied
        if ($student->$student()->where('id_offre', $request->offer_id)->exists()) {
            throw ValidationException::withMessages([
                'offer_id' => ['You have already applied to this offer.']
            ]);
        }

        // Check offer application limit
        if ($offer->applications()->count() >= $offer->max_applications) {
            throw ValidationException::withMessages([
                'offer_id' => ['This offer has reached maximum applications.']
            ]);
        }

        $application = Application::create([
            'id_student' => $student->id,
            'id_offre' => $request->offer_id,
            'date' => now()
        ]);

        return response()->json($application->load('offer'), 201);
    }

    // Delete application
    public function destroy($applicationId)
    {
        $application = Application::findOrFail($applicationId);

        // Authorization check
        if ($application->id_student != Auth::guard('student')->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $application->delete();
        return response()->json(null, 204);
    }
}
