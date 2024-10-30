<?php

namespace App\Http\Controllers;

use App\Models\CDR;
use App\Models\Payroll;
use App\Models\Beneficiary;
use App\Models\Designation;
use App\Models\Entity;
use App\Models\UacsCode;
use App\Models\PaymentNature;
use App\Models\DvPayroll;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // Added missing Cache facade

class CDRController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = CDR::with(['payroll'])
                ->when($request->search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('cdrName', 'like', "%{$search}%")
                            ->orWhere('cdrID', 'like', "%{$search}%")
                            ->orWhereHas('payroll', function ($q) use ($search) {
                                $q->where('payrollNumber', 'like', "%{$search}%")
                                    ->orWhere('payrollName', 'like', "%{$search}%");
                            });
                    });
                })
                ->when($request->sort_field, function ($query, $sort_field) use ($request) {
                    $direction = $request->sort_direction ?? 'asc';
                    if ($sort_field === 'payrollNumber' || $sort_field === 'payrollName') {
                        $query->join('payrolls', 'cdrs.payrollID', '=', 'payrolls.payrollID')
                            ->orderBy($sort_field, $direction);
                    } else {
                        $query->orderBy($sort_field, $direction);
                    }
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                });

            $cdrs = $query->paginate(10)
                ->withQueryString()
                ->through(fn($cdr) => [
                    'cdrID' => $cdr->cdrID,
                    'payrollID' => $cdr->payrollID,  // Make sure this is included
                    'cdrName' => $cdr->cdrName,
                    'created_at' => $cdr->created_at,
                    'payroll' => [
                        'payrollID' => $cdr->payroll->payrollID,
                        'payrollNumber' => $cdr->payroll->payrollNumber,
                        'payrollName' => $cdr->payroll->payrollName,
                    ]
                ]);

            return Inertia::render('User/cdrForm', [
                'cdrs' => $cdrs,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CDR index: ' . $e->getMessage());
            return Inertia::render('User/cdrForm', [
                'cdrs' => [],
                'error' => 'Failed to load CDRs. Please try again later.'
            ]);
        }
    }

    public function getBeneficiaries(Request $request)
    {
        try {
            $validated = $request->validate([
                'payrollID' => ['required', 'exists:payrolls,payrollID'],
                'page' => ['sometimes', 'integer', 'min:1'],
                'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            ]);

            $payroll = Payroll::findOrFail($validated['payrollID']);
            $perPage = $request->input('per_page', 12);
            $page = $request->input('page', 1);

            $cacheKey = sprintf(
                'beneficiaries:%s:page:%d:per_page:%d',
                $payroll->payrollNumber,
                $page,
                $perPage
            );

            $beneficiaries = Cache::remember(
                $cacheKey,
                now()->addMinutes(5),
                function () use ($payroll, $perPage) {
                    return Beneficiary::where('payrollNumber', $payroll->payrollNumber)
                        ->select([
                            'beneficiaryID',
                            'payrollNumber',
                            'lastName',
                            'firstName',
                            'middleName',
                            'extensionName',
                            'status'
                        ])
                        ->orderBy('lastName')
                        ->orderBy('firstName')
                        ->paginate($perPage)
                        ->through(function ($beneficiary) {
                            return [
                                'id' => $beneficiary->beneficiaryID,
                                'payrollNumber' => $beneficiary->payrollNumber,
                                'lastName' => $beneficiary->lastName,
                                'firstName' => $beneficiary->firstName,
                                'middleName' => $beneficiary->middleName,
                                'extensionName' => $beneficiary->extensionName,
                                'fullName' => trim(
                                    $beneficiary->lastName . ', ' .
                                    $beneficiary->firstName . ' ' .
                                    ($beneficiary->middleName ? $beneficiary->middleName . ' ' : '') .
                                    ($beneficiary->extensionName ? $beneficiary->extensionName : '')
                                ),
                                'status' => $this->getStatusLabel($beneficiary->status)
                            ];
                        });
                }
            );

            return response()->json($beneficiaries);
        } catch (\Exception $e) {
            Log::error('Error fetching beneficiaries: ' . $e->getMessage(), [
                'payrollID' => $request->input('payrollID'),
                'page' => $request->input('page'),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'error' => 'Failed to fetch beneficiaries',
                'message' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    // Helper method to the controller
    private function getStatusLabel($status)
    {
        return match ($status) {
            1 => 'Claimed',
            2 => 'Unclaimed',
            3 => 'Disqualified',
            4 => 'Double Entry',
            default => 'Unknown'
        };
    }

    public function searchPayrolls(Request $request)
    {
        try {
            $payrolls = Cache::remember('payrolls', now()->addHours(1), function () {
                return Payroll::select('payrollID', 'payrollNumber', 'payrollName')
                    ->orderBy('payrollNumber')
                    ->get();
            });

            return response()->json($payrolls);
        } catch (\Exception $e) {
            Log::error('Error searching payrolls: ' . $e->getMessage(), [
                'user_id' => auth()->id()
            ]);
            return response()->json(['error' => 'Failed to search payrolls'], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cdrName' => ['required', 'string', 'max:35'],
            'payrollID' => ['required', 'exists:payrolls,payrollID'],
        ]);

        try {
            DB::beginTransaction();

            // Generate next CDR ID
            $lastCdr = CDR::orderBy('cdrID', 'desc')->first();
            $nextCdrID = $lastCdr
                ? str_pad((intval($lastCdr->cdrID) + 1), 6, '0', STR_PAD_LEFT)
                : '000001';

            // Create new CDR
            $cdr = new CDR();
            $cdr->cdrID = $nextCdrID;
            $cdr->cdrName = $validated['cdrName'];
            $cdr->payrollID = $validated['payrollID'];
            $cdr->save();

            // Clear relevant caches
            Cache::forget('payrolls');

            DB::commit();

            return redirect()->back()->with('success', 'CDR created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create CDR: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'payload' => $validated
            ]);

            return redirect()->back()->withErrors([
                'generalError' => 'Failed to create CDR. Please try again later.'
            ]);
        }
    }

    public function addOption(Request $request)
    {
        try {
            DB::beginTransaction();

            $result = null;

            switch ($request->category) {
                case 'entityNameFundCluster':
                    $validated = $request->validate([
                        'data.entityName' => 'required|string|max:25',
                        'data.fundCluster' => 'required|string|max:30',
                    ]);

                    $result = Entity::create([
                        'entityName' => $validated['data']['entityName'],
                        'fundCluster' => $validated['data']['fundCluster']
                    ]);
                    break;

                case 'officerDesignationStation':
                    $validated = $request->validate([
                        'data.accountableOfficer' => 'required|string|max:30',
                        'data.officialDesignation' => 'required|string|max:10',
                        'data.station' => 'required|string|max:10',
                    ]);

                    $result = Designation::create([
                        'accountableOfficer' => $validated['data']['accountableOfficer'],
                        'officialDesignation' => $validated['data']['officialDesignation'],
                        'station' => $validated['data']['station']
                    ]);
                    break;

                case 'referenceNumber':
                    $validated = $request->validate([
                        'data.dvPNumber' => 'required|string|max:11|unique:dvpayrolls,dvPNumber',
                        'data.checkNo' => 'required|integer|min:1|max:999999',
                    ]);

                    $result = DvPayroll::create([
                        'dvPNumber' => $validated['data']['dvPNumber'],
                        'check_no' => $validated['data']['checkNo']
                    ]);
                    break;

                case 'uacsCode':
                    $validated = $request->validate([
                        'data.uacsObjectCode' => 'required|string|max:11|unique:uacscodes,uacsObjectCode',
                    ]);

                    $result = UacsCode::create([
                        'uacsObjectCode' => $validated['data']['uacsObjectCode']
                    ]);
                    break;

                case 'natureOfPayment':
                    $validated = $request->validate([
                        'data.natureOfPayment' => 'required|string|max:60',
                    ]);

                    $result = PaymentNature::create([
                        'natureOfPayment' => $validated['data']['natureOfPayment']
                    ]);
                    break;

                default:
                    throw new \Exception('Invalid category');
            }

            if (!$result) {
                throw new \Exception('Failed to create record');
            }

            DB::commit();

            return response()->json([
                'message' => 'Option added successfully',
                'status' => 'success',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding option: ' . $e->getMessage(), [
                'category' => $request->category,
                'data' => $request->data,
                'user_id' => auth()->id()
            ]);

            $errorMessage = config('app.debug') ? $e->getMessage() : 'An unexpected error occurred';

            return response()->json([
                'message' => 'Failed to add option',
                'error' => $errorMessage
            ], 500);
        }
    }

    public function getOptions()
    {
        try {
            $data = [
                'entities' => Entity::select('entityID', 'entityName', 'fundCluster')->get(),
                'designations' => Designation::select('designationID', 'accountableOfficer', 'officialDesignation', 'station')->get(),
                'dvPayrolls' => DVPayroll::select('dvPNumber', 'check_no')->get(),
                'uacsCodes' => UACSCode::select('uacsObjectCode')->get(),
                'paymentsNature' => PaymentsNature::select('nOPId', 'natureOfPayment')->get(),
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Error fetching options: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch options'], 500);
        }
    }
}
