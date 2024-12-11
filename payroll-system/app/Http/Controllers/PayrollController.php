<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Beneficiary;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;


class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::query()
            ->join('barangays', 'payrolls.barangayID', '=', 'barangays.barangayID')
            ->join('municipalities', 'barangays.municipalityID', '=', 'municipalities.municipalityID')
            ->select('payrolls.*', 'municipalities.municipalityName', 'barangays.barangayName');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('payrolls.payrollNumber', 'like', "%{$search}%")
                    ->orWhere('payrolls.payrollName', 'like', "%{$search}%")
                    ->orWhere('municipalities.municipalityName', 'like', "%{$search}%")
                    ->orWhere('barangays.barangayName', 'like', "%{$search}%")
                    ->orWhere('payrolls.created_at', 'like', "%{$search}%");
            });
        }

        $payrolls = $query->paginate(10)->withQueryString();

        return Inertia::render('User/PayrollForm', [
            'payrolls' => $payrolls
        ]);
    }

    public function store(Request $request)
{
    Log::info('Received payroll data:', $request->all());

    $validated = $request->validate([
        'payrollName' => 'required|string|max:50',
        'barangayID' => 'required|exists:barangays,barangayID',
    ]);

    do {
        $payrollNumber = $this->generatePayrollNumber();
    } while (Payroll::where('payrollNumber', $payrollNumber)->exists());

    $barangay = Barangay::findOrFail($validated['barangayID']);
    $subTotal = $barangay->totalAmountReleased;

    DB::transaction(function () use ($payrollNumber, $validated, $subTotal, $barangay) {
        $payroll = Payroll::create([
            'payrollNumber' => $payrollNumber,
            'payrollName' => $validated['payrollName'],
            'barangayID' => $validated['barangayID'],
            'subTotal' => $subTotal,
        ]);


        Log::info('Created payroll:', $payroll->toArray());
    });

    return redirect()->route('payroll.index')->with('success', 'Payroll created successfully.');
}

    private function generatePayrollNumber()
    {
        $number = '';
        for ($i = 0; $i < 15; $i++) {
            $number .= mt_rand(0, 9);
        }
        if ($number[0] === '0') {
            $number[0] = mt_rand(1, 9);
        }
        return $number;
    }

    public function getProvinces()
    {
        $provinces = Province::all();
        return response()->json(['provinces' => $provinces]);
    }

    public function getMunicipalities(Request $request)
    {
        $municipalities = Municipality::where('provinceID', $request->provinceID)->get();
        return response()->json(['municipalities' => $municipalities]);
    }

    public function getBarangays(Request $request)
    {
        $barangays = Barangay::where('municipalityID', $request->municipalityID)->get();
        return response()->json(['barangays' => $barangays]);
    }

    public function searchUnvalidatedBeneficiaries(Request $request, $payrollId)
{
    try {
        $payroll = Payroll::findOrFail($payrollId);
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $beneficiaries = Beneficiary::where('barangayID', $payroll->barangayID)
            ->whereNull('payrollNumber')
            ->where(function ($query) use ($search) {
                $query->where('lastName', 'like', "%{$search}%")
                    ->orWhere('firstName', 'like', "%{$search}%")
                    ->orWhere('middleName', 'like', "%{$search}%");
            })
            ->paginate($perPage);

        return response()->json([
            'payroll' => $payroll,
            'beneficiaries' => $beneficiaries,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred while searching beneficiaries: ' . $e->getMessage()], 500);
    }
}

public function validateBeneficiary(Request $request, $payrollId, $beneficiaryId)
{
    try {
        $payroll = Payroll::findOrFail($payrollId);
        $beneficiary = Beneficiary::findOrFail($beneficiaryId);

        // Validate that the beneficiary belongs to the same barangay
        if ($beneficiary->barangayID !== $payroll->barangayID) {
            return response()->json(['error' => 'Beneficiary does not belong to the same barangay'], 400);
        }

        // Generate a unique beneficiary number
        $maxBeneficiaryNumber = Beneficiary::where('payrollNumber', $payroll->payrollNumber)
            ->max('beneficiaryNumber');

        DB::transaction(function () use ($payroll, $beneficiary, $maxBeneficiaryNumber) {
            $beneficiary->update([
                'payrollNumber' => $payroll->payrollNumber,
                'status' => 5, // Validated status
                'beneficiaryNumber' => $maxBeneficiaryNumber ? $maxBeneficiaryNumber + 1 : 1
            ]);

            // Optional: Update the payroll subtotal
            $beneficiary->amount = $beneficiary->amount ?? 0;
            $payroll->increment('subTotal', $beneficiary->amount);
        });

        return response()->json([
            'message' => 'Beneficiary validated successfully',
            'beneficiary' => $beneficiary
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred while validating beneficiary: ' . $e->getMessage()], 500);
    }
}


public function getBeneficiaries(Request $request, $payrollId)
{
    try {
        $payroll = Payroll::findOrFail($payrollId);
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);

        $beneficiaries = Beneficiary::where('barangayID', $payroll->barangayID)
            ->where('status', 5)
            ->where(function ($query) use ($search) {
                $query->where('beneficiaryNumber', 'like', "%{$search}%")
                    ->orWhere('lastName', 'like', "%{$search}%")
                    ->orWhere('firstName', 'like', "%{$search}%")
                    ->orWhere('middleName', 'like', "%{$search}%");
            })
            ->orderBy('beneficiaryNumber', 'asc')  // Add this line for sorting
            ->paginate($perPage);

        return response()->json([
            'payroll' => $payroll,
            'beneficiaries' => $beneficiaries,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An error occurred while fetching beneficiaries: ' . $e->getMessage()], 500);
    }
}

    public function markAllClaimed($payrollId)
    {
        try {
            $payroll = Payroll::findOrFail($payrollId);

            DB::transaction(function () use ($payroll) {
                Beneficiary::where('payrollNumber', $payroll->payrollNumber)
                    ->update(['status' => 1]);

                $payroll->subTotal = 0;
                $payroll->save();
            });

            return response()->json(['message' => 'All beneficiaries have been marked as claimed.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while marking beneficiaries as claimed: ' . $e->getMessage()], 500);
        }
    }

    public function updateBeneficiaries(Request $request, $payrollId)
    {
        // Update validation rules to accept status 5
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0|max:99999.99',
            'beneficiaries' => 'array',
            'beneficiaries.*.beneficiaryID' => 'sometimes|exists:beneficiaries,beneficiaryID',
            'beneficiaries.*.status' => 'sometimes|integer|min:1|max:5', // Updated max to 5
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', [
                'errors' => $validator->errors(),
                'input' => $request->all()
            ]);

            return response()->json([
                'errors' => $validator->errors(),
                'input' => $request->all()
            ], 422);
        }

        try {
            $validated = $validator->validated();
            $payroll = Payroll::findOrFail($payrollId);

            DB::transaction(function () use ($payroll, $validated) {
                $amount = number_format($validated['amount'], 2, '.', '');

                // Update amount ONLY for beneficiaries with status 5
                Beneficiary::where('barangayID', $payroll->barangayID)
                    ->where('status', 5)
                    ->update(['amount' => $amount]);

                // If specific beneficiary statuses are provided, validate they're status 5 before updating
                if (!empty($validated['beneficiaries'])) {
                    foreach ($validated['beneficiaries'] as $beneficiary) {
                        if (($beneficiary['status'] ?? null) === 5) {
                            Beneficiary::where('beneficiaryID', $beneficiary['beneficiaryID'])
                                ->where('barangayID', $payroll->barangayID)
                                ->where('status', 5) // Only update if status is 5
                                ->update([
                                    'amount' => $amount
                                ]);
                        }
                    }
                }

                // Recalculate total amount for validated (status 5) beneficiaries
                $totalAmount = Beneficiary::where('barangayID', $payroll->barangayID)
                    ->where('status', 5)
                    ->sum('amount');

                // Ensure subTotal doesn't exceed 99999.99
                $subTotal = number_format(min($totalAmount, 999999.99), 2, '.', '');

                // Update payroll subtotal
                $payroll->update(['subTotal' => $subTotal]);
            });

            return response()->json(['message' => 'Beneficiaries updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating beneficiaries', [
                'payrollId' => $payrollId,
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while updating beneficiaries: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function export($payrollId)
    {
        try {
            $payroll = Payroll::with([
                'barangay.municipality.province',
                'beneficiaries' => function($query) {
                    $query->where('status', 5)
                        ->orderBy('beneficiaryNumber', 'asc');
                }
            ])->findOrFail($payrollId);

            // Check if there are any status 5 beneficiaries
            if ($payroll->beneficiaries->isEmpty()) {
                return response()->json([
                    'error' => 'There are no validated beneficiaries (status 5) to export.',
                    'status' => 'empty'
                ], 400);
            }

            $pdf = $this->generatePdf($payroll);

            DB::transaction(function () use ($payroll) {
                // Always allow export by removing any status checks

                // Increment exportNum
                $payroll->increment('exportNum');

                // Recalculate and update subtotal for status 5 beneficiaries only
                $subtotal = $payroll->beneficiaries()
                    ->where('status', 5)
                    ->sum('amount');

                // Ensure subtotal doesn't exceed 99999.99
                $payroll->subTotal = number_format(min($subtotal, 999999.99), 2, '.', '');
                $payroll->updated_at = now();
                $payroll->save();

                // Log the export for tracking
                Log::info('Payroll exported', [
                    'payroll_id' => $payroll->id,
                    'payroll_number' => $payroll->payrollNumber,
                    'export_number' => $payroll->exportNum,
                    'beneficiary_count' => $payroll->beneficiaries->count(),
                    'total_amount' => $subtotal,
                    'exported_at' => now()->toDateTimeString()
                ]);
            });

            // Include export number in filename for tracking multiple exports
            $timestamp = now()->format('Ymd_His');
            $filename = sprintf(
                "payroll_%s_export%d_%s.pdf",
                $payroll->payrollNumber,
                $payroll->exportNum,
                $timestamp
            );

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage(), [
                'payroll_id' => $payrollId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'PDF generation failed: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    private function generatePdf(Payroll $payroll)
    {
        // Group beneficiaries into chunks of 10 for pagination
        $beneficiariesChunks = $payroll->beneficiaries->chunk(10);

        $pdf = PDF::loadView('pdfs.payroll', [
            'payroll' => $payroll,
            'beneficiaries' => $beneficiariesChunks,
            'totalPages' => $beneficiariesChunks->count(),
            'totalBeneficiaries' => $payroll->beneficiaries->count(),
            'totalAmount' => $payroll->beneficiaries->sum('amount'),
            'exportDateTime' => now()->format('F j, Y g:i A')
        ]);

        $pdf->setPaper('legal', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
        ]);

        return $pdf;
    }
}
