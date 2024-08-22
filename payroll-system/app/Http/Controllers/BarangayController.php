<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Barangay;
use App\Models\Beneficiary;
use App\Models\Municipality;

class BarangayController extends Controller
{
    public function index($municipalityID)
    {
        $barangays = Barangay::where('municipalityID', $municipalityID)->get();
        $municipality = Municipality::where('municipalityID', $municipalityID)->first();

        return Inertia::render('User/Barangays', [
            'barangays' => $barangays->toArray(),
            'municipalityID' => (int) $municipalityID,
            'municipalityName' => $municipality->municipalityName,
        ]);
    }
    public function masterlist($barangayID)
    {
        $barangay = Barangay::where('barangayID', $barangayID)->first();
        $beneficiaries = Beneficiary::where('barangayID', $barangayID)->get();

        return Inertia::render('User/Masterlists', [
            'barangay' => $barangay->toArray(),
            'beneficiaries' => $beneficiaries->toArray(),
        ]);
    }
}
