<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function update(Request $request)
    {
        try {
            // Get authenticated company
            $company = Auth::guard('company')->user();
            if (!$company) {
                return response()->json(['error' => 'Unauthorized. Company authentication required.'], 401);
            }

            // Validate request data
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:companies,email,' . $company->id,
                'domain' => 'sometimes|string|max:255',
                'address' => 'sometimes|string|max:255',
                'country' => 'sometimes|string|max:255',
                'ville' => 'sometimes|string|max:255',
                'rc' => 'sometimes|string|max:255',
                'date' => 'sometimes|date',
                'website' => 'sometimes|nullable|url|max:255',
                'logo' => 'sometimes|nullable|url|max:255',
                'description' => 'sometimes|nullable|string',
                'password' => 'sometimes|string|min:6|confirmed',
            ]);

            // Update company information
            $updateData = $request->only([
                'name',
                'email',
                'domain',
                'address',
                'country',
                'ville',
                'rc',
                'date',
                'website',
                'logo',
                'description'
            ]);

            // If password is provided, hash it
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Update using query builder
            DB::table('companies')
                ->where('id', $company->id)
                ->update($updateData);

            // Get updated company data
            $updatedCompany = Company::find($company->id);

            return response()->json([
                'message' => 'Company information updated successfully',
                'company' => $updatedCompany
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update company information',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }
}
