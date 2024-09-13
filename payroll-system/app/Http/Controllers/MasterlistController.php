<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterlistController extends Controller
{
    public function index(Request $request)
    {
        $barangayID = $request->input('barangayID');
        $masterlists = Masterlist::where('barangayID', $barangayID)
            ->get(['masterlistID', 'masterlistName'])
            ->toArray();

        return response()->json(['masterlists' => $masterlists]);
    }
}
