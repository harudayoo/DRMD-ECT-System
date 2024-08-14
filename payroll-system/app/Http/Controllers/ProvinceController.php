<?php
use App\Http\Controllers\Controller;

use App\Models\Province;
use Inertia\Inertia;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::all();

        return Inertia::render('ProvinceIndex', [
            'provinces' => $provinces,
        ]);
    }
}
