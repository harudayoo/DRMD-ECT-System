<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Beneficiary;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        \Log::info('Received payroll data:', $request->all());

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

            // Update beneficiaries with the new payrollNumber
            Beneficiary::where('barangayID', $barangay->barangayID)
                ->whereNull('payrollNumber')
                ->update(['payrollNumber' => $payrollNumber]);

            \Log::info('Created payroll:', $payroll->toArray());
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

    public function getBeneficiaries(Request $request, $payrollId)
    {
        try {
            $payroll = Payroll::findOrFail($payrollId);
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 10);

            $beneficiaries = Beneficiary::where('payrollNumber', $payroll->payrollNumber)
                ->where(function ($query) use ($search) {
                    $query->where('beneficiaryNumber', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%")
                        ->orWhere('firstName', 'like', "%{$search}%")
                        ->orWhere('middleName', 'like', "%{$search}%");
                })
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

    public function export($payrollId)
    {
        try {
            $payroll = Payroll::with([
                'barangay.municipality.province',
                'beneficiaries'
            ])->findOrFail($payrollId);

            $claimedCount = $payroll->beneficiaries->where('status', 1)->count();
            $unclaimedCount = $payroll->beneficiaries->where('status', 2)->count();

            if ($unclaimedCount === 0) {
                return response()->json(['error' => 'There are no beneficiaries to export.'], 400);
            }

            if ($claimedCount > 0) {
                return response()->json([
                    'message' => "{$claimedCount} Beneficiaries claimed, {$unclaimedCount} to export."
                ], 200);
            }

            $pdf = $this->generatePdf($payroll);

            // Increment exportNum
            $payroll->increment('exportNum');

            // Recalculate and update subtotal
            $subtotal = $payroll->beneficiaries()
                ->where('status', 2) // Only include unclaimed beneficiaries
                ->sum('amount');

            // Ensure subtotal doesn't exceed 99999.99 (max value for decimal(8,2))
            $payroll->subTotal = number_format(min($subtotal, 999999.99), 2, '.', '');
            $payroll->updated_at = now();
            $payroll->save();

            return $pdf->download("payroll_{$payroll->payrollNumber}.pdf");
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: ' . $e->getMessage(), ['payroll_id' => $payrollId]);
            return response()->json(['error' => 'PDF generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function updateBeneficiaries(Request $request, $payrollId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:99999.99',
            'beneficiaries' => 'required|array',
            'beneficiaries.*.beneficiaryID' => 'required|exists:beneficiaries,beneficiaryID',
            'beneficiaries.*.status' => 'required|integer|min:1|max:4',
        ]);

        try {
            $payroll = Payroll::findOrFail($payrollId);

            DB::transaction(function () use ($payroll, $validated) {
                $amount = number_format($validated['amount'], 2, '.', '');

                // Update amount for all beneficiaries in the barangay
                Beneficiary::where('barangayID', $payroll->barangayID)
                    ->update(['amount' => $amount]);

                // Update status for specific beneficiaries
                foreach ($validated['beneficiaries'] as $beneficiary) {
                    Beneficiary::where('beneficiaryID', $beneficiary['beneficiaryID'])
                        ->update(['status' => $beneficiary['status']]);
                }

                // Recalculate total amount for unclaimed beneficiaries
                $totalAmount = Beneficiary::where('barangayID', $payroll->barangayID)
                    ->where('status', 2) // Only include unclaimed beneficiaries
                    ->sum('amount');

                // Ensure subTotal doesn't exceed 99999.99
                $subTotal = number_format(min($totalAmount, 999999.99), 2, '.', '');

                // Update payroll subtotal
                $payroll->update(['subTotal' => $subTotal]);
            });

            return response()->json(['message' => 'Beneficiaries updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Error updating beneficiaries: ' . $e->getMessage(), [
                'payrollId' => $payrollId,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while updating beneficiaries: ' . $e->getMessage()], 500);
        }
    }

    private function generatePdf(Payroll $payroll)
    {
        $pdf = PDF::loadView('pdfs.payroll', [
            'payroll' => $payroll,
            'beneficiaries' => $payroll->beneficiaries->chunk(10),
        ]);

        $pdf->setPaper('legal', 'landscape');
        $pdf->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
        ]);

        return $pdf;
    }
}
