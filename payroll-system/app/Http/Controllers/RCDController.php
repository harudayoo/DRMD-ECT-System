<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\RCD;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RCDController extends Controller
{
    public function index(Request $request)
    {
        $query = RCD::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('rcdName', 'like', "%{$searchTerm}%");
        }

        $rcds = $query->paginate(10)->withQueryString();
        $payrolls = Payroll::select('payrollID', 'payrollNumber', 'payrollName')->get();

        return Inertia::render('User/rcdForm', [
            'rcds' => $rcds,
            'payrolls' => $payrolls,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rcdName' => 'required|string|max:255',
            'payrollID' => 'required|exists:payrolls,payrollID',
        ]);

        try {
            DB::beginTransaction();

            $lastRcd = RCD::orderBy('rcdID', 'desc')->first();
            $nextRcdID = $lastRcd ? str_pad((intval($lastRcd->rcdID) + 1), 6, '0', STR_PAD_LEFT) : '000001';

            $rcd = new RCD();
            $rcd->rcdID = $nextRcdID;
            $rcd->rcdName = $request->rcdName;
            $rcd->payrollID = $request->payrollID;
            $rcd->save();

            DB::commit();

            return redirect()->back()->with('success', 'RCD created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['generalError' => 'Failed to create RCD: ' . $e->getMessage()]);
        }
    }

    public function getBeneficiaries(Request $request)
    {
        try {
            $request->validate([
                'payrollID' => 'required|exists:payrolls,payrollID',
            ]);

            $payroll = Payroll::findOrFail($request->payrollID);
            Log::info("Fetching beneficiaries for payrollID: {$request->payrollID}, payrollNumber: {$payroll->payrollNumber}");

            $beneficiaries = Beneficiary::where('payrollNumber', $payroll->payrollNumber)->get();
            Log::info("Found " . $beneficiaries->count() . " beneficiaries");

            return response()->json($beneficiaries);
        } catch (\Exception $e) {
            Log::error("Error fetching beneficiaries: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
