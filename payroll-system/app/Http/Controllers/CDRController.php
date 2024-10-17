<?php

namespace App\Http\Controllers;

use App\Models\RCD;
use App\Models\CDR;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CDRController extends Controller
{
    public function index(Request $request)
    {
        $query = CDR::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cdrName', 'like', "%{$search}%")
                        ->orWhere('cdrID', 'like', "%{$search}%");
                });
            })
            ->when($request->sort_field, function ($query, $sort_field) use ($request) {
                $direction = $request->sort_direction ?? 'asc';
                $query->orderBy($sort_field, $direction);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            });

        $cdrs = $query->paginate(10)
            ->withQueryString()
            ->through(fn($cdr) => [
                'cdrID' => $cdr->cdrID,
                'rcdID' => $cdr->rcdID,
                'cdrName' => $cdr->cdrName,
                'created_at' => $cdr->created_at
            ]);

        // Fetch sorted RCDs
        $rcds = RCD::select('rcdID', 'rcdName')
            ->orderBy('rcdID')
            ->get();

        return Inertia::render('User/cdrForm', [
            'cdrs' => $cdrs,
            'rcds' => $rcds,
        ]);
    }

    public function searchRcds(Request $request)
    {
        $search = $request->input('search');

        $rcds = RCD::select('rcdID', 'rcdName')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('rcdID', 'like', "%{$search}%")
                        ->orWhere('rcdName', 'like', "%{$search}%");
                });
            })
            ->orderBy('rcdID')
            ->get();

        return response()->json($rcds);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cdrName' => ['required', 'string', 'max:35'],
            'rcdID' => ['required', 'exists:rcds,rcdID'],
        ]);

        try {
            DB::beginTransaction();

            // Get the last CDR and generate the next ID
            $lastCdr = CDR::orderBy('cdrID', 'desc')->first();
            $nextCdrID = $lastCdr
                ? str_pad((intval($lastCdr->cdrID) + 1), 6, '0', STR_PAD_LEFT)
                : '000001';

            // Create new CDR
            $cdr = new CDR();
            $cdr->cdrID = $nextCdrID;
            $cdr->cdrName = $validated['cdrName'];
            $cdr->rcdID = $validated['rcdID'];
            $cdr->save();

            DB::commit();

            return redirect()->back()->with('success', 'CDR created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create CDR: ' . $e->getMessage());

            return redirect()->back()->withErrors([
                'generalError' => 'Failed to create CDR. Please try again later.'
            ]);
        }
    }

    public function show($rcdID)
    {
        $rcd = RCD::with('payroll')->findOrFail($rcdID);
        return response()->json([
            'rcdID' => $rcd->rcdID,
            'rcdName' => $rcd->rcdName,
            'payrollID' => $rcd->payroll->payrollID,

        ]);
    }

    public function getBeneficiaries(Request $request)
    {
        try {
            $validated = $request->validate([
                'rcdID' => ['required', 'exists:rcds,rcdID'],
            ]);

            $rcd = RCD::with('payroll')->findOrFail($validated['rcdID']);

            Log::info("Fetching beneficiaries for rcdID: {$validated['rcdID']}");

            $beneficiaries = Beneficiary::where('payrollNumber', $rcd->payroll->payrollNumber)
                ->get();

            Log::info("Found " . $beneficiaries->count() . " beneficiaries");

            return response()->json($beneficiaries);
        } catch (\Exception $e) {
            Log::error("Error fetching beneficiaries: " . $e->getMessage());

            return response()->json([
                'error' => 'Failed to fetch beneficiaries. Please try again later.'
            ], 500);
        }
    }
}
