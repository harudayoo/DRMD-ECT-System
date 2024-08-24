<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getStatusAnalytics($municipalityId)
    {
        $statusCounts = DB::table('beneficiaries')
            ->join('barangays', 'beneficiaries.barangayID', '=', 'barangays.barangayID')
            ->where('barangays.municipalityID', $municipalityId)
            ->select('beneficiaries.status', DB::raw('count(*) as count'))
            ->groupBy('beneficiaries.status')
            ->get();

        $statusNames = DB::table('status_table')->pluck('status_name', 'status_id');

        $formattedCounts = $statusCounts->map(function ($item) use ($statusNames) {
            return [
                'label' => $statusNames[$item->status] ?? 'Unknown',
                'value' => $item->count
            ];
        });

        return response()->json($formattedCounts);
    }
}
