<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Beneficiary;

class SearchController extends Controller
{
    /**
     * Search for municipalities, barangays, and beneficiaries.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');

        // Search municipalities
        $municipalities = Municipality::where('municipalityName', 'LIKE', "%{$query}%")->get();

        // Search barangays
        $barangays = Barangay::where('barangayName', 'LIKE', "%{$query}%")->get();

        // Search beneficiaries
        $beneficiaries = Beneficiary::where('beneficiaryID', 'LIKE', "%{$query}%")->get();

        return response()->json([
            'municipalities' => $municipalities,
            'barangays' => $barangays,
            'beneficiaries' => $beneficiaries,
        ]);
    }

    // Other methods (index, create, store, show, edit, update, destroy) remain unchanged
}
