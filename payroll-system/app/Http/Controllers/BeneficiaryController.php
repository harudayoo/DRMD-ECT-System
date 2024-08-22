<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Beneficiary;
use App\Models\Municipality;
use App\Models\Province;
use Inertia\Inertia;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function create()
    {
        $provinces = Province::all(['provinceID', 'provinceName'])->toArray();
        return Inertia::render('User/AddBeneficiary', [
            'provinces' => $provinces,
            'municipalities' => [],
            'barangays' => [],
        ]);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'lastName' => 'required|string|max:255',
                'firstName' => 'required|string|max:255',
                'middleName' => 'required|string|max:255',
                'extensionName' => 'nullable|string|max:255',
                'barangayID' => 'required|exists:barangays,barangayID',
                'municipalityID' => 'required|exists:municipalities,municipalityID',
                'provinceID' => 'required|exists:provinces,provinceID',
                'dateOfBirth' => 'required|date',
            ]);

            $validatedData['status'] = 2; // Set the status to 2 upon adding
            $validatedData['beneficiaryNumber'] = Beneficiary::generateUniqueBeneficiaryNumber($validatedData['barangayID']);

            $beneficiary = Beneficiary::create($validatedData);

            return response()->json(['message' => 'Beneficiary added successfully', 'beneficiary' => $beneficiary], 201);
        } catch (\Exception $e) {
            \Log::error('Error adding beneficiary: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while adding the beneficiary'], 500);
        }
    }

    public function getMunicipalities(Request $request)
    {
        $provinceID = $request->input('provinceID');
        $municipalities = Municipality::where('provinceID', $provinceID)
            ->get(['municipalityID', 'municipalityName'])
            ->toArray();

        return response()->json(['municipalities' => $municipalities]);
    }

    public function getBarangays(Request $request)
    {
        $municipalityID = $request->input('municipalityID');
        $barangays = Barangay::where('municipalityID', $municipalityID)
            ->get(['barangayID', 'barangayName'])
            ->toArray();

        return response()->json(['barangays' => $barangays]);
    }
}
