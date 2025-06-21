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
    public function index(Request $request)
    {
        try {
            $student = Auth::guard('student')->user();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized. Student authentication required.'], 401);
            }

            $query = SavedOffer::where('id_student', $student->id)
                ->with(['offer.company', 'offer.jobtype']);

            // Add search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->whereHas('offer', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('Job_Descriptin', 'like', "%{$search}%")
                        ->orWhere('skills', 'like', "%{$search}%");
                });
            }

            $savedOffers = $query->get();

            return response()->json($savedOffers);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch saved offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Save an offer (student only)
    public function store(Request $request)
    {
        try {
            $student = Auth::guard('student')->user();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized. Student authentication required.'], 401);
            }

            $validated = $request->validate([
                'id_offre' => 'required|exists:offers,id'
            ]);

            // Check if offer is already saved
            $existingSave = SavedOffer::where('id_student', $student->id)
                ->where('id_offre', $validated['id_offre'])
                ->first();

            if ($existingSave) {
                return response()->json([
                    'message' => 'Offer already saved',
                    'saved' => true
                ]);
            }

            $savedOffer = SavedOffer::create([
                'id_student' => $student->id,
                'id_offre' => $validated['id_offre']
            ]);

            return response()->json([
                'message' => 'Offer saved successfully',
                'saved' => true,
                'saved_offer' => $savedOffer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save offer',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function destroy($offerId)
    {
        try {
            $student = Auth::guard('student')->user();
            if (!$student) {
                return response()->json(['error' => 'Unauthorized. Student authentication required.'], 401);
            }

            $savedOffer = SavedOffer::where('id_student', $student->id)
                ->where('id_offre', $offerId)
                ->first();

            if (!$savedOffer) {
                return response()->json(['message' => 'Offer not found in saved offers'], 404);
            }

            $savedOffer->delete();

            return response()->json([
                'message' => 'Offer unsaved successfully',
                'saved' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unsave offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
