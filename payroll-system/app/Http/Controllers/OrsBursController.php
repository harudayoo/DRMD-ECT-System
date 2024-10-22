<?php

namespace App\Http\Controllers;

use App\Models\OrsBurs;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class OrsBursController extends Controller
{
    public function index()
    {
        try {
            $orsBurs = OrsBurs::all()->map(function ($item) {
                return [
                    'id' => $item->orsBursNumber,
                    'value' => $item->orsBursNumber,
                    'label' => $item->orsBursNumber
                ];
            });

            Log::info('OrsBurs items fetched:', ['count' => $orsBurs->count()]);

            return response()->json($orsBurs);
        } catch (\Exception $e) {
            Log::error('Error fetching OrsBurs items: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while fetching ORS/BURS items.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'orsBurs' => 'required|string|max:11|unique:orsburs,orsBursNumber',
            ], [
                'orsBurs.required' => 'ORS/BURS Number is required.',
                'orsBurs.unique' => 'This ORS/BURS Number already exists.',
                'orsBurs.max' => 'ORS/BURS Number must not exceed 11 characters.',
            ]);

            $orsBurs = OrsBurs::create([
                'orsBursNumber' => $validated['orsBurs']
            ]);

            return response()->json([
                'id' => $orsBurs->orsBursNumber,
                'value' => $orsBurs->orsBursNumber,
                'message' => 'ORS/BURS added successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the ORS/BURS.',
                'errors' => ['general' => [$e->getMessage()]]
            ], 500);
        }
    }
}
