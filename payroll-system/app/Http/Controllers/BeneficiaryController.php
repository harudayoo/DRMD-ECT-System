<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Beneficiary;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Masterlist;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BeneficiaryController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $beneficiaries = Beneficiary::all();
        return Inertia::render('User/EditBeneficiary', [
            'beneficiaries' => $beneficiaries
        ]);
    }

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
            'masterlistID' => 'required|exists:masterlists,masterlistID',
            'dateOfBirth' => 'required|date',
            'sex' => 'required|in:Male,Female',
        ]);

        $similarBeneficiaries = $this->findSimilarBeneficiaries($validatedData);

        if (!empty($similarBeneficiaries)) {
            return response()->json([
                'message' => 'Similar beneficiaries found',
                'similarBeneficiaries' => $similarBeneficiaries
            ], 200);
        }

        // Transform sex value from string to integer
        $validatedData['sex'] = $validatedData['sex'] === 'Male' ? 1 : 2;

        // Set the status to 2 upon adding
        $validatedData['status'] = 2;
        
        // Set beneficiaryNumber to null
        $validatedData['beneficiaryNumber'] = null;

        $beneficiary = Beneficiary::create($validatedData);

        return response()->json(['message' => 'Beneficiary added successfully', 'beneficiary' => $beneficiary], 201);
    } catch (\Exception $e) {
        Log::error('Error adding beneficiary: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred while adding the beneficiary', 'error' => $e->getMessage()], 500);
    }
}

public function confirmAdd(Request $request)
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
            'masterlistID' => 'required|exists:masterlists,masterlistID',
            'dateOfBirth' => 'required|date',
            'address' => 'required|string|max:255',
            'contactNumber' => 'required|string|max:11',
            'sex' => 'required|in:Male,Female',
        ]);

        // Transform sex value from string to integer
        $validatedData['sex'] = $validatedData['sex'] === 'Male' ? 1 : 2;

        $validatedData['status'] = 4; // Set status to 4 for potential duplicate
        
        // Set beneficiaryNumber to null
        $validatedData['beneficiaryNumber'] = null;

        $beneficiary = Beneficiary::create($validatedData);

        return response()->json(['message' => 'Beneficiary added successfully as potential duplicate', 'beneficiary' => $beneficiary], 201);
    } catch (\Exception $e) {
        Log::error('Error adding beneficiary: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred while adding the beneficiary'], 500);
    }
}

    public function findSimilarBeneficiaries($data)
    {
        $query = Beneficiary::query();

        // Stage 1: Find potentially similar beneficiaries
        $potentialMatches = $query->where(function ($q) use ($data) {
            $q->where('lastName', 'like', '%' . $data['lastName'] . '%')
                ->orWhere('firstName', 'like', '%' . $data['firstName'] . '%')
                ->orWhere('middleName', 'like', '%' . $data['middleName'] . '%')
                ->orWhere('extensionName', 'like', '%' . $data['extensionName'] . '%');
        })->get();

        $similarBeneficiaries = [];

        foreach ($potentialMatches as $beneficiary) {
            $lastNameSimilarity = $this->calculateSimilarity($data['lastName'], $beneficiary->lastName);
            $firstNameSimilarity = $this->calculateSimilarity($data['firstName'], $beneficiary->firstName);
            $middleNameSimilarity = $this->calculateSimilarity($data['middleName'], $beneficiary->middleName);
            $extensionNameSimilarity = $this->calculateSimilarity($data['extensionName'], $beneficiary->extensionName);

            $overallSimilarity = ($lastNameSimilarity + $firstNameSimilarity + $middleNameSimilarity + $extensionNameSimilarity) / 4;

            if ($overallSimilarity >= 0.7) { // 80% similarity threshold
                $similarBeneficiaries[] = [
                    'beneficiary' => $beneficiary,
                    'similarity' => $overallSimilarity
                ];
            }
        }

        // Sort similar beneficiaries by similarity (descending order)
        usort($similarBeneficiaries, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return array_slice($similarBeneficiaries, 0, 5); // Return top 5 similar beneficiaries
    }

    private function calculateSimilarity($str1, $str2)
    {
        $str1 = strtolower($str1);
        $str2 = strtolower($str2);
        $length = max(strlen($str1), strlen($str2));
        if ($length > 0) {
            return (1 - levenshtein($str1, $str2) / $length);
        }
        return 1; // If both strings are empty, consider them identical
    }

    public function update(Request $request, $beneficiaryID)
    {
        try {
            $validatedData = $request->validate([
                'lastName' => 'required|string|max:255',
                'firstName' => 'required|string|max:255',
                'middleName' => 'required|string|max:255',
                'extensionName' => 'nullable|string|max:255',
                'dateOfBirth' => 'required|date',
                'sex' => 'required|in:Male,Female',
            ]);

            $beneficiary = Beneficiary::findOrFail($beneficiaryID);


        // Transform sex value
        $validatedData['sex'] = $validatedData['sex'] === 'Male' ? 1 : 2;

            $beneficiary->update($validatedData);

            return response()->json($beneficiary);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Beneficiary not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating beneficiary: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while updating the beneficiary'], 500);
        }
    }

    public function getProvinces()
    {
        try {
            $provinces = Province::all(['provinceID', 'provinceName']);
            return response()->json(['provinces' => $provinces]);
        } catch (\Exception $e) {
            \Log::error('Error fetching provinces: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch provinces'], 500);
        }
    }

    public function getMunicipalities(Request $request)
    {
        $provinceID = $request->query('provinceID');
        Log::info('getMunicipalities called with provinceID: ' . $provinceID);

        if (!$provinceID) {
            Log::warning('Province ID is missing');
            return response()->json(['error' => 'Province ID is required'], 400);
        }

        try {
            $municipalities = Municipality::where('provinceID', $provinceID)
                ->get(['municipalityID', 'municipalityName']);

            Log::info('Municipalities fetched: ' . $municipalities->count());
            return response()->json(['municipalities' => $municipalities]);
        } catch (\Exception $e) {
            Log::error('Error fetching municipalities: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch municipalities'], 500);
        }
    }

    public function getBarangays(Request $request)
    {
        $municipalityID = $request->query('municipalityID');
        Log::info('getBarangays called with municipalityID: ' . $municipalityID);

        try {
            $barangays = Barangay::where('municipalityID', $municipalityID)
                ->get(['barangayID', 'barangayName']);

            Log::info('Barangays fetched: ' . $barangays->count());
            return response()->json(['barangays' => $barangays]);
        } catch (\Exception $e) {
            Log::error('Error fetching barangays: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch barangays'], 500);
        }
    }

    public function getMasterlists(Request $request)
    {
        $municipalityID = $request->query('municipalityID');
        Log::info('getMasterlists called with municipalityID: ' . $municipalityID);

        try {
            $masterlists = Masterlist::where('municipalityID', $municipalityID)
                ->get(['masterlistID', 'masterlistName']);

            Log::info('Masterlists fetched: ' . $masterlists->count());
            return response()->json(['masterlists' => $masterlists]);
        } catch (\Exception $e) {
            Log::error('Error fetching masterlists: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch masterlists'], 500);
        }
    }
    public function getByPayrollID(Request $request)
    {
        $payrollID = $request->query('payrollID');
        $beneficiaries = Beneficiary::where('payrollID', $payrollID)->get();
        return response()->json($beneficiaries);
    }
}
