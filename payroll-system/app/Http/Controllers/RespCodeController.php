<?php

namespace App\Http\Controllers;

use App\Models\RespCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class RespCodeController extends Controller
{
    public function index()
    {
        try {
            $respCodes = RespCode::all()->map(function ($item) {
                return [
                    'id' => $item->responsibilityCode,
                    'value' => $item->responsibilityCode,
                    'label' => $item->responsibilityCode
                ];
            });

            Log::info('RespCode items fetched:', ['count' => $respCodes->count()]);

            return response()->json($respCodes);
        } catch (\Exception $e) {
            Log::error('Error fetching RespCode items: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching Responsibility Center Code items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'responsibilityCode' => 'required|string|max:15|unique:respcodes,responsibilityCode',
            ], [
                'responsibilityCode.required' => 'Responsibility Center Code is required.',
                'responsibilityCode.unique' => 'This Responsibility Center Code already exists.',
                'responsibilityCode.max' => 'Responsibility Center Code must not exceed 15 characters.',
            ]);

            $respCode = RespCode::create($validated);

            return response()->json([
                'id' => $respCode->responsibilityCode,
                'value' => $respCode->responsibilityCode,
                'message' => 'Responsibility Center Code added successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the Responsibility Center Code.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
