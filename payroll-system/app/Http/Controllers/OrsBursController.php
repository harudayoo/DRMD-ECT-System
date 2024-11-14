<?php
namespace App\Http\Controllers;

use App\Models\OrsBurs;
use App\Http\Requests\StoreOrsBursRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class OrsBursController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $orsBurs = OrsBurs::select('orsBursNumber')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->orsBursNumber,
                        'value' => $item->orsBursNumber,
                        'label' => $item->orsBursNumber
                    ];
                });

            return response()->json($orsBurs);
        } catch (\Exception $e) {
            Log::error('Error fetching OrsBurs items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch ORS/BURS items.'
            ], 500);
        }
    }

    public function store(StoreOrsBursRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $orsBurs = OrsBurs::create([
                'orsBursNumber' => strtoupper($validated['orsBurs'])
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $orsBurs->orsBursNumber,
                    'value' => $orsBurs->orsBursNumber,
                    'label' => $orsBurs->orsBursNumber,
                ],
                'message' => 'ORS/BURS added successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating OrsBurs:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add ORS/BURS.'
            ], 500);
        }
    }
}
