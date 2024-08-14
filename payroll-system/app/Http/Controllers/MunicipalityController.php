<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index($provinceID)
    {
        $municipalities = Municipality::where('provinceID', $provinceID)->get();

        return Inertia::render('Municipalities', [
            'municipalities' => $municipalities,
            'provinceID' => $provinceID,
        ]);
    }
}
