<?php

namespace App\Http\Controllers;

use App\Models\UacsCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class UacsCodeController extends Controller
{
    public function index()
    {
        try {
            $uacsCodes = UacsCode::all()->map(function ($item) {
                return [
                    'id' => $item->uacsObjectCode,
                    'value' => $item->uacsObjectCode,
                    'label' => $item->uacsObjectCode
                ];
            });

            Log::info('UacsCode items fetched:', ['count' => $uacsCodes->count()]);

            return response()->json($uacsCodes);
        } catch (\Exception $e) {
            Log::error('Error fetching UacsCode items: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching UACS Object Code items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'uacsObjectCode' => 'required|string|max:11|unique:uacscodes,uacsObjectCode',
            ], [
                'uacsObjectCode.required' => 'UACS Object Code is required.',
                'uacsObjectCode.unique' => 'This UACS Object Code already exists.',
                'uacsObjectCode.max' => 'UACS Object Code must not exceed 11 characters.',
            ]);

            $uacsCode = UacsCode::create($validated);

            return response()->json([
                'id' => $uacsCode->uacsObjectCode,
                'value' => $uacsCode->uacsObjectCode,
                'message' => 'UACS Object Code added successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the UACS Object Code.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
