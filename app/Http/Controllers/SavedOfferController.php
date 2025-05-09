<?php

namespace App\Http\Controllers;

use App\Models\SavedOffer;
use App\Models\Offer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SavedOfferController extends Controller
{
    // Get all saved offers for current student
    public function index()
    {
        $student = Auth::guard('student')->user();
        return $student->$student::with(['offer.company', 'offer.jobtype'])
            ->get();
    }

    // Save an offer (student only)
    public function store(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id'
        ]);

        $student = Auth::guard('student')->user();

        // Check if already saved
        if ($student->$student()->where('id_offre', $request->offer_id)->exists()) {
            throw ValidationException::withMessages([
                'offer_id' => ['This offer is already saved.']
            ]);
        }

        $savedOffer = SavedOffer::create([
            'id_student' => $student->id,
            'id_offre' => $request->offer_id,
            'date' => now()
        ]);

        return response()->json($savedOffer->load('offer'), 201);
    }

    // Check if offer is saved
    public function show($offerId)
    {
        $student = Auth::guard('student')->user();
        $isSaved = $student->$student
            ->where('id_offre', $offerId)
            ->exists();

        return response()->json(['saved' => $isSaved]);
    }

    // Remove saved offer
    public function destroy($savedOfferId)
    {
        $savedOffer = SavedOffer::findOrFail($savedOfferId);

        if ($savedOffer->id_student != Auth::guard('student')->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $savedOffer->delete();
        return response()->json(null, 204);
    }
}
