<?php

namespace App\Http\Controllers;


use App\Models\RespCode;
use App\Http\Requests\StoreRespCodeRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class RespCodeController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $respCodes = RespCode::select('responsibilityCode')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->responsibilityCode,
                        'value' => $item->responsibilityCode,
                        'label' => $item->responsibilityCode
                    ];
                });

            return response()->json($respCodes);
        } catch (\Exception $e) {
            Log::error('Error fetching RespCode items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch Responsibility Center Code items.'
            ], 500);
        }
    }

    public function store(StoreRespCodeRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $respCode = RespCode::create([
                'responsibilityCode' => strtoupper($validated['responsibilityCode'])
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $respCode->responsibilityCode,
                    'value' => $respCode->responsibilityCode,
                    'label' => $respCode->responsibilityCode,
                ],
                'message' => 'Responsibility Center Code added successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating RespCode:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add Responsibility Center Code.'
            ], 500);
        }
    }
}
