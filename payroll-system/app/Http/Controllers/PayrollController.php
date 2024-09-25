<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use Inertia\Inertia;

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

        // Generate a unique 15-digit payroll number
        do {
            $payrollNumber = $this->generatePayrollNumber();
        } while (Payroll::where('payrollNumber', $payrollNumber)->exists());

        // Fetch the totalAmountReleased from the selected barangay
        $barangay = Barangay::findOrFail($validated['barangayID']);
        $subTotal = $barangay->totalAmountReleased;

        // Create the new payroll
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
        // Generate a random 15-digit number as a string
        $number = '';
        for ($i = 0; $i < 15; $i++) {
            $number .= mt_rand(0, 9);
        }
        // Ensure the first digit is not zero
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

}
