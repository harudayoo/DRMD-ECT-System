<?php

namespace App\Http\Controllers;

use App\Models\CDR;
use App\Models\RCD;
use App\Models\Beneficiary;
use App\Models\OrsBurs;
use App\Models\RespCode;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RCDController extends Controller
{
    public function index(Request $request)
    {
        $query = RCD::query()
            ->with(['CDR', 'orsBurs', 'respCode']);

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('rcdName', 'like', "%{$searchTerm}%");
        }

        $rcds = $query->paginate(10)->withQueryString();
        $cdrs = CDR::select('cdrID', 'cdrName')->get();
        $orsBurs = OrsBurs::select('orsBursNumber')->get();
        $respCodes = RespCode::select('responsibilityCode')->get();

        return Inertia::render('User/rcdForm', [
            'rcds' => $rcds,
            'cdrs' => $cdrs,
            'orsBurs' => $orsBurs,
            'respCodes' => $respCodes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rcdName' => [
                'required',
                'string',
                'min:3',
                'max:35', // Updated to match char(35) constraint
                Rule::unique('rcds', 'rcdName'),
            ],
            'cdrID' => [
                'required',
                'exists:cdrs,cdrID',
            ],
        ], [
            'rcdName.required' => 'The RCD name is required.',
            'rcdName.min' => 'The RCD name must be at least 3 characters.',
            'rcdName.max' => 'The RCD name cannot exceed 35 characters.',
            'rcdName.unique' => 'This RCD name is already taken.',
            'cdrID.required' => 'Please select a CDR.',
            'cdrID.exists' => 'The selected CDR does not exist.',
        ]);

        try {
            DB::beginTransaction();

            // Create the new RCD record with auto-incrementing ID
            $rcd = new RCD();
            $rcd->rcdName = $request->rcdName;
            $rcd->cdrID = $request->cdrID;
            $rcd->save();

            DB::commit();

            return redirect()->back()->with('success', 'RCD created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RCD Creation Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['generalError' => 'Failed to create RCD. Please try again.']);
        }
    }

    public function getBeneficiaries(Request $request)
    {
        try {
            $request->validate([
                'cdrID' => 'required|exists:cdrs,cdrID',
            ]);

            $cdr = CDR::findOrFail($request->cdrID);
            Log::info("Fetching beneficiaries for cdrID: {$request->cdrID}, cdrID: {$cdr->cdrID}");

            $beneficiaries = Beneficiary::where('cdrID', $cdr->cdrID)->get();
            Log::info("Found " . $beneficiaries->count() . " beneficiaries");

            return response()->json($beneficiaries);
        } catch (\Exception $e) {
            Log::error("Error fetching beneficiaries: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function export($rcdID)
    {
        try {
            $rcd = RCD::with([
                'cdr',
                'dvPayroll',
                'orsBurs',
                'respCode',
                'uacsCode',
                'paymentNature'
            ])->findOrFail($rcdID);

            $beneficiaries = Beneficiary::where('cdrID', $rcd->cdr->cdrID)
                ->orderBy('beneficiaryNumber')
                ->get();

            $beneficiaryGroups = $beneficiaries->chunk(10);

            $municipalityIds = $beneficiaries->pluck('barangayID')->unique();
            $municipalities = Municipality::whereHas('barangays', function ($query) use ($municipalityIds) {
                $query->whereIn('barangayID', $municipalityIds);
            })->pluck('municipalityName')->implode(', ');

            $groupedData = [];
            foreach ($beneficiaryGroups as $group) {
                $firstBeneficiary = $group->first();
                $totalAmount = $group->sum('amount');

                $groupedData[] = [
                    'date' => Carbon::parse($rcd->updated_at)->format('d-M-y'),
                    'dvNumber' => $rcd->dvNumber,
                    'orsNumber' => $rcd->orsNumber,
                    'responCode' => $rcd->responCode,
                    'payee' => sprintf(
                        "%s (%s, %s %s ET AL.)",
                        $municipalities,
                        $firstBeneficiary->lastName,
                        $firstBeneficiary->firstName,
                        $firstBeneficiary->middleName
                    ),
                    'uacsCode' => $rcd->uacsCode,
                    'paymentNature' => $rcd->paymentNature,
                    'amount' => number_format($totalAmount, 2)
                ];
            }

            $totalAmount = $beneficiaries->sum('amount');

            $pdf = PDF::loadView('exports.rcd', [
                'rcd' => $rcd,
                'groupedData' => $groupedData,
                'totalAmount' => $totalAmount,
                'period' => Carbon::parse($rcd->cdr->updated_at)->format('F d, Y'),
                'reportNumber' => Carbon::now()->format('Y-m-d') . '-' . $rcd->rcdID,
                'appendixNumber' => '41'
            ]);

            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['defaultFont' => 'arial']);

            return $pdf->download('RCD-' . $rcd->rcdID . '.pdf');

        } catch (\Exception $e) {
            Log::error("PDF Export Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF'
            ], 500);
        }
    }
}
