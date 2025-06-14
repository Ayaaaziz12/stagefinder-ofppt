<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Offer::with('company')
                ->select([
                    'offers.id',
                    'offers.title',
                    'offers.Job_Descriptin as description',
                    'offers.skills as requirements',
                    'offers.expiration_date',
                    'offers.max_applications',
                    'offers.id_company',
                    'offers.created_at',
                    'companies.name as company',
                    'companies.ville as location',
                    'companies.email as company_email',
                ])
                ->join('companies', 'offers.id_company', '=', 'companies.id');

            // Search functionality
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('offers.title', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('offers.Job_Descriptin', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('companies.name', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $offers = $query->latest('offers.created_at')->paginate($perPage);

            // Transform the data to match the frontend structure
            $transformedOffers = $offers->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'company' => $offer->company,
                    'location' => $offer->location,
                    'salary' => '', // No salary field in offers, leave blank or map if available
                    'type' => '',   // No type field in offers, leave blank or map if available
                    'posted' => $offer->created_at ? $offer->created_at->diffForHumans() : '',
                    'description' => $offer->description,
                    'requirements' => $offer->requirements ? explode(',', $offer->requirements) : [],
                    'company_email' => $offer->company_email,
                ];
            });

            return response()->json([
                'jobs' => $transformedOffers,
                'pagination' => [
                    'total' => $offers->total(),
                    'per_page' => $offers->perPage(),
                    'current_page' => $offers->currentPage(),
                    'last_page' => $offers->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch job listings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
