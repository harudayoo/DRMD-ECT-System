<?php

namespace App\Http\Controllers;

use App\Models\PaymentNature;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PaymentNatureController extends Controller
{
    public function index()
    {
        try {
            $paymentNatures = PaymentNature::all()->map(function ($item) {
                return [
                    'id' => $item->nOPId,
                    'value' => $item->natureOfPayment,
                    'label' => $item->natureOfPayment
                ];
            });

            Log::info('PaymentNature items fetched:', ['count' => $paymentNatures->count()]);

            return response()->json($paymentNatures);
        } catch (\Exception $e) {
            Log::error('Error fetching PaymentNature items: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching Nature of Payment items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'natureOfPayment' => 'required|string|max:255',
            ], [
                'natureOfPayment.required' => 'Nature of Payment is required.',
                'natureOfPayment.max' => 'Nature of Payment must not exceed 255 characters.',
            ]);

            $paymentNature = PaymentNature::create($validated);

            return response()->json([
                'id' => $paymentNature->nOPId,
                'value' => $paymentNature->natureOfPayment,
                'message' => 'Nature of Payment added successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the Nature of Payment.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
