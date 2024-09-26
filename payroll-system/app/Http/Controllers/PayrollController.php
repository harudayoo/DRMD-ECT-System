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

        $payroll = Payroll::create([
            'payrollNumber' => $payrollNumber,
            'payrollName' => $validated['payrollName'],
            'barangayID' => $validated['barangayID'],
            'subTotal' => $subTotal,
        ]);

        \Log::info('Created payroll:', $payroll->toArray());

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
            $payroll = Payroll::with('beneficiaries')->findOrFail($payrollId);
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 10);

            $beneficiaries = $payroll->beneficiaries()
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

    public function updateBeneficiaries(Request $request, $payrollId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'beneficiaries' => 'required|array',
            'beneficiaries.*.beneficiaryID' => 'required|exists:beneficiaries,beneficiaryID',
            'beneficiaries.*.status' => 'required|integer|min:1|max:4',
        ]);

        try {
            $payroll = Payroll::findOrFail($payrollId);

            DB::transaction(function () use ($payroll, $validated) {
                Beneficiary::where('barangayID', $payroll->barangayID)
                    ->update(['amount' => $validated['amount']]);

                foreach ($validated['beneficiaries'] as $beneficiary) {
                    Beneficiary::where('beneficiaryID', $beneficiary['beneficiaryID'])
                        ->update(['status' => $beneficiary['status']]);
                }

                $totalAmount = Beneficiary::where('barangayID', $payroll->barangayID)
                    ->where('status', '!=', 4)
                    ->sum('amount');

                $payroll->update(['subTotal' => $totalAmount]);
            });

            return response()->json(['message' => 'Beneficiaries updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating beneficiaries.'], 500);
        }
    }
}
