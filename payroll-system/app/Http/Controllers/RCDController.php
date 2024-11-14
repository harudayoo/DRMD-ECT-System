<?php

namespace App\Http\Controllers;

use App\Models\CDR;
use App\Models\RCD;
use App\Models\Payroll;
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

    public function getBeneficiaries(RCD $rcd)
    {
        try {
            // Debug initial RCD
            Log::info('Fetching beneficiaries for RCD:', [
                'rcdID' => $rcd->rcdID,
                'cdrID' => $rcd->cdrID,
                'rcdName' => $rcd->rcdName
            ]);

            if (!$rcd->cdrID) {
                return response()->json([
                    'message' => 'This RCD has no associated CDR',
                    'rcdName' => $rcd->rcdName
                ], 404);
            }

            // First, get the CDR
            $cdr = CDR::where('cdrID', $rcd->cdrID)->first();

            // Debug CDR
            Log::info('CDR found:', [
                'cdrID' => $cdr?->cdrID,
                'payrollID' => $cdr?->payrollID,
                'cdrName' => $cdr?->cdrName
            ]);

            if (!$cdr) {
                return response()->json([
                    'message' => 'CDR not found',
                    'rcdName' => $rcd->rcdName
                ], 404);
            }

            // Get the Payroll
            $payroll = Payroll::where('payrollID', $cdr->payrollID)->first();

            // Debug Payroll
            Log::info('Payroll found:', [
                'payrollID' => $payroll?->payrollID,
                'payrollNumber' => $payroll?->payrollNumber,
                'payrollName' => $payroll?->payrollName
            ]);

            if (!$payroll) {
                return response()->json([
                    'message' => 'No payroll found for this CDR',
                    'cdrName' => $cdr->cdrName ?? 'Unknown'
                ], 404);
            }

            // Get beneficiaries
            $beneficiaries = Beneficiary::where('payrollNumber', $payroll->payrollNumber)
                ->get([
                    'beneficiaryNumber',
                    'lastName',
                    'firstName',
                    'middleName',
                    'extensionName',
                    'amount',
                    'status',
                    'payrollNumber'
                ]);

            // Debug Beneficiaries
            Log::info('Beneficiaries query:', [
                'payrollNumber' => $payroll->payrollNumber,
                'count' => $beneficiaries->count(),
                'sql' => Beneficiary::where('payrollNumber', $payroll->payrollNumber)->toSql()
            ]);

            if ($beneficiaries->isEmpty()) {
                return response()->json([
                    'message' => 'No beneficiaries found for this payroll',
                    'payrollNumber' => $payroll->payrollNumber,
                    'payrollName' => $payroll->payrollName
                ], 404);
            }

            return response()->json([
                'beneficiaries' => $beneficiaries,
                'metadata' => [
                    'rcdName' => $rcd->rcdName,
                    'orsNumber' => $rcd->orsNumber,
                    'responCode' => $rcd->responCode,
                    'cdrName' => $cdr->cdrName ?? 'Unknown',
                    'dvPNumber' => $cdr->dvPNumber ?? 'Unknown',
                    'payrollNumber' => $payroll->payrollNumber ?? 'Unknown',
                    'payrollName' => $payroll->payrollName ?? 'Unknown',
                    'totalAmount' => $beneficiaries->sum('amount'),
                    'totalBeneficiaries' => $beneficiaries->count(),
                    'cashAdvanceReceived' => $cdr->cashAdvanceReceived ?? 0,
                    'subTotal' => $payroll->subTotal ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching beneficiaries:', [
                'rcdID' => $rcd->rcdID,
                'cdrID' => $rcd->cdrID,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred while fetching beneficiaries',
                'detail' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request, $rcdID)
    {
        try {
            DB::beginTransaction();

            $rcd = RCD::findOrFail($rcdID);

            $validatedData = $request->validate([
                'orsNumber' => [
                    'sometimes',
                    'string',
                    Rule::exists('orsburs', 'orsBursNumber'), // Updated table name
                ],
                'responCode' => [
                    'sometimes',
                    'string',
                    Rule::exists('respcodes', 'responsibilityCode'), // Updated table name
                ],
            ], [
                'orsNumber.exists' => 'The selected ORS/BURS number does not exist in our records.',
                'responCode.exists' => 'The selected Responsibility Center Code does not exist.',
            ]);

            $updates = array_filter($validatedData, function ($value) {
                return $value !== null;
            });

            if (!empty($updates)) {
                $rcd->update($updates);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'RCD updated successfully',
                'data' => $rcd->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RCD Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update RCD: ' . $e->getMessage()
            ], 500);
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
