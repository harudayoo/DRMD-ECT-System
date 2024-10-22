<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\RCD;
use App\Models\Beneficiary;
use App\Models\DvPayroll;
use App\Models\OrsBurs;
use App\Models\RespCode;
use App\Models\UacsCode;
use App\Models\PaymentNature;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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

    public function update(Request $request, $rcdID)
    {
        Log::info("Updating RCD {$rcdID} with data:", $request->all());

        try {
            $validated = $request->validate([
                'dvNumber' => [
                    'nullable',
                    Rule::exists('dvpayrolls', 'dvPNumber')
                ],
                'orsNumber' => [
                    'nullable',
                    Rule::exists('orsburs', 'orsBursNumber')
                ],
                'responCode' => [
                    'nullable',
                    Rule::exists('respcodes', 'responsibilityCode')
                ],
                'uacsCode' => [
                    'nullable',
                    Rule::exists('uacscodes', 'uacsObjectCode')
                ],
                'paymentNature' => [
                    'nullable',
                    Rule::exists('paymentsnature', 'natureOfPayment')
                ]
            ]);

            $rcd = RCD::findOrFail($rcdID);

            // Clean and prepare the update data
            $updateData = array_filter($validated, function ($value) {
                return !is_null($value) && $value !== '';
            });

            DB::beginTransaction();

            $rcd->update($updateData);

            // Reload the relations
            $rcd->load(['respCode', 'orsBurs', 'uacsCode', 'paymentNature']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $rcd,
                'message' => 'RCD updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Validation error updating RCD: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating RCD: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update RCD',
                'error' => app()->environment('local') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    private function generateNextRcdID()
    {
        $lastRcd = RCD::orderBy('rcdID', 'desc')->first();
        return $lastRcd
            ? str_pad((intval($lastRcd->rcdID) + 1), 6, '0', STR_PAD_LEFT)
            : '000001';
    }
}
