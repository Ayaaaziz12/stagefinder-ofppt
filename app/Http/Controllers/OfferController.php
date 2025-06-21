<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Jobtype;
use App\Models\Offrestatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    // Get all offers with relationships
    public function index(Request $request)
    {
        $query = Offer::with(['company', 'jobtype', 'offrestatus']);

        // Filter by job type if provided
        if ($request->has('job_type_id')) {
            $query->where('id_JobType', $request->job_type_id);
        }

        // Filter by company if provided
        if ($request->has('company_id')) {
            $query->where('id_company', $request->company_id);
        }

        // Filter by status if provided
        if ($request->has('status_id')) {
            $query->where('id_OffreStatus', $request->status_id);
        }

        // Filter by search term if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('Job_Descriptin', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        // Sort by date (newest first)
        $query->orderBy('created_at', 'desc');

        $offers = $query->get();

        return response()->json([
            'message' => 'Offers retrieved successfully',
            'offers' => $offers
        ]);
    }

    // Create new offer (company only)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'Job_Descriptin' => 'required|string',
                'skills' => 'required|string',
                'expiration_date' => 'required|date|after:today',
                'max_applications' => 'required|integer|min:1',
                'id_JobType' => [
                    'required',
                    Rule::exists('jobtypes', 'id')
                ],
                'id_OffreStatus' => [
                    'sometimes',
                    Rule::exists('offrestatuses', 'id')
                ]
            ]);

            $company = Auth::guard('company')->user();
            if (!$company) {
                return response()->json(['error' => 'Unauthorized. Company authentication required.'], 401);
            }

            $offer = Offer::create([
                'title' => $validated['title'],
                'Job_Descriptin' => $validated['Job_Descriptin'],
                'skills' => $validated['skills'],
                'expiration_date' => $validated['expiration_date'],
                'max_applications' => $validated['max_applications'],
                'id_company' => $company->id,
                'id_JobType' => $validated['id_JobType'],
                'id_OffreStatus' => $validated['id_OffreStatus'] ?? null
            ]);

            return response()->json([
                'message' => 'Offer created successfully',
                'offer' => $offer->load(['company', 'jobtype', 'offrestatus'])
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get single offer with relationships
    public function show($offerId)
    {
        $offer = Offer::with(['company', 'jobtype', 'offrestatus', 'applications'])
            ->findOrFail($offerId);

        return response()->json($offer);
    }

    // Update offer (company only)
    public function update(Request $request, Offer $offer)
    {
        // Authorization check
        if ($offer->id_company != Auth::guard('company')->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'Job_Descriptin' => 'sometimes|string',
            'max_applications' => 'sometimes|integer|min:1',
            'id_JobType' => [
                'sometimes',
                Rule::exists('jobtypes', 'id')
            ],
            'id_OffreStatus' => [
                'sometimes',
                Rule::exists('offrestatuses', 'id')
            ]
        ]);

        $offer->update($request->all());
        return response()->json($offer->fresh(['company', 'jobtype', 'offrestatus']));
    }

    // Delete offer (company only)
    public function destroy($offerId)
    {
        $offer = Offer::findOrFail($offerId);

        // Authorization check
        if ($offer->id_company != Auth::guard('company')->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete all saved offers linked to this offer
        \App\Models\SavedOffer::where('id_offre', $offerId)->delete();

        $offer->delete();
        return response()->json([
            'message' => 'Offer deleted successfully',
            'offer_id' => $offerId
        ], 200);
    }
}
