<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Inertia\Inertia;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::all();

        return Inertia::render('User/MainDash', [
            'provinces' => $provinces,
        ]);
    }
}
