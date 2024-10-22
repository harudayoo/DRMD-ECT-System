<?php

namespace App\Http\Controllers;

use App\Models\DvPayroll;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class DvPayrollController extends Controller
{
    public function index()
    {
        try {
            $dvPayrolls = DvPayroll::all()->map(function ($item) {
                return [
                    'id' => $item->dvPNumber,
                    'value' => $item->dvPNumber,
                    'label' => $item->dvPName
                ];
            });

            Log::info('DVPayroll items fetched:', ['count' => $dvPayrolls->count()]);

            return response()->json($dvPayrolls);
        } catch (\Exception $e) {
            Log::error('Error fetching DVPayroll items: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching DV Payroll items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'dvPNumber' => 'required|string|max:11|unique:dvpayrolls,dvPNumber',
                'dvPName' => 'required|string|max:255',
            ], [
                'dvPNumber.required' => 'DV Payroll Number is required.',
                'dvPNumber.unique' => 'This DV Payroll Number already exists.',
                'dvPNumber.max' => 'DV Payroll Number must not exceed 11 characters.',
                'dvPName.required' => 'DV Payroll Name is required.',
                'dvPName.max' => 'DV Payroll Name must not exceed 255 characters.',
            ]);

            $dvPayroll = DvPayroll::create($validated);

            return response()->json([
                'id' => $dvPayroll->dvPNumber,
                'value' => $dvPayroll->dvPNumber,
                'dvPName' => $dvPayroll->dvPName,
                'message' => 'DV Payroll added successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the DV Payroll.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
