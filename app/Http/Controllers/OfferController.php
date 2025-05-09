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
    public function index()
    {
        return Offer::with(['company', 'jobtype', 'offrestatus'])->get();
    }

    // Create new offer (company only)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'Job_Descriptin' => 'required|string',
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

        $offer = Offer::create([
            'title' => $request->title,
            'Job_Descriptin' => $request->Job_Descriptin,
            'date' => now(), // Auto-set creation date
            'expiration_date' => $request->expiration_date,
            'max_applications' => $request->max_applications,
            'id_company' => Auth::guard('company')->user()->id,
            'id_JobType' => $request->id_JobType,
            'id_OffreStatus' => $request->id_OffreStatus
        ]);

        return response()->json($offer->load(['company', 'jobtype', 'offrestatus']), 201);
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
            'expiration_date' => 'sometimes|date|after:today',
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

        $offer->delete();
        return response()->json(null, 204);
    }
}
