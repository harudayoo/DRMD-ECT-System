<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Province;
use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index($provinceID)
    {
        $municipalities = Municipality::where('provinceID', $provinceID)
            ->select(
                'municipalityName',
                'municipalityID',
                'provinceID',
                'totalBeneficiaries',
                'totalAmountReleased',
                'claimed',
                'unclaimed',
                'disqualified',
                'double_entry'
            )
            ->get();
        $province = Province::find($provinceID);

        return Inertia::render('Municipalities', [
            'municipalities' => $municipalities,
            'provinceID' => $provinceID,
            'provinceName' => $province->name,
        ]);
    }
}
