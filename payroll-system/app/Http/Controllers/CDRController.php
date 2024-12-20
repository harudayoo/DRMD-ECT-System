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


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
            5 => 'Validated',
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
                'paymentsNature' => PaymentNature::select('nOPId', 'natureOfPayment')->get(),
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Error fetching options: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch options'], 500);
        }
    }

    public function update(Request $request, $cdrID)
    {
        try {
            $validated = $request->validate([
                'entityID' => 'required|exists:entities,entityID',
                'designationID' => 'required|exists:designations,designationID',
                'dvPNumber' => 'required|exists:dvpayrolls,dvPNumber',
                'uacsObjectCode' => 'required|exists:uacscodes,uacsObjectCode',
                'nOPId' => 'required|exists:paymentsnature,nOPId',
                'cashAdvanceReceived' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $cdr = CDR::findOrFail($cdrID);

            // Check if fields are already filled
            $filledFields = [];
            foreach ($validated as $field => $value) {
                if ($cdr->$field !== null) {
                    $filledFields[] = $field;
                }
            }

            if (!empty($filledFields)) {
                return response()->json([
                    'error' => 'Some fields are already filled',
                    'filledFields' => $filledFields
                ], 422);
            }

            $cdr->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'CDR updated successfully',
                'cdr' => $cdr
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating CDR: ' . $e->getMessage(), [
                'cdrID' => $cdrID,
                'data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Failed to update CDR',
                'message' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }

    public function exportPDF($cdrID)
    {
        try {
            // Fetch CDR with all related data using proper relationships
            $cdr = CDR::with([
                'entity',
                'designation',
                'dvPayroll',
                'uacsCode',
                'paymentNature',
                'payroll',
                'payroll.beneficiariesPayroll' => function ($query) {
                    $query->select([
                        'beneficiaries.*',
                        'barangays.barangayName',
                        'municipalities.municipalityName'
                    ])
                        ->leftJoin('barangays', 'beneficiaries.barangayID', '=', 'barangays.barangayID')
                        ->leftJoin('municipalities', 'barangays.municipalityID', '=', 'municipalities.municipalityID')
                        ->orderBy('beneficiaries.lastName')
                        ->orderBy('beneficiaries.firstName');
                }
            ])->findOrFail($cdrID);

            // Format the data for PDF
            $data = [
                'entityName' => $cdr->entity->entityName ?? 'N/A',
                'fundCluster' => $cdr->entity->fundCluster ?? 'N/A',
                'accountableOfficer' => $cdr->designation->accountableOfficer ?? 'N/A',
                'officialDesignation' => $cdr->designation->officialDesignation ?? 'N/A',
                'station' => $cdr->designation->station ?? 'N/A',
                'appendixNumber' => $cdr->appendixNumber ?? '',
                'sheetNumber' => $cdr->sheetNumber ?? '1',
                'cdr_date' => $cdr->created_at->format('m/d/Y'),
                'referenceNumber' => $cdr->dvPayroll->dvPNumber ?? 'N/A',
                'checkNumber' => $cdr->dvPayroll->check_no ?? 'N/A',
                'uacsObjectCode' => $cdr->uacsCode->uacsObjectCode ?? 'N/A',
                'natureOfPayment' => $cdr->paymentNature->natureOfPayment ?? 'N/A',
                'cashAdvanceReceived' => $cdr->cashAdvanceReceived ?? 0,
                'beneficiaries' => $cdr->payroll->beneficiariesPayroll->map(function ($beneficiary) {
                    return [
                        'lastName' => $beneficiary->lastName ?? '',
                        'firstName' => $beneficiary->firstName ?? '',
                        'middleName' => $beneficiary->middleName ?? '',
                        'barangayName' => $beneficiary->barangayName ?? 'N/A',
                        'municipalityName' => $beneficiary->municipalityName ?? 'N/A',
                        'amount' => $beneficiary->amount ?? 0,
                        'payrollNo' => $beneficiary->payrollNumber ?? 'N/A',
                        'updated_at' => $beneficiary->updated_at
                    ];
                })->toArray()
            ];

            // Generate PDF with custom options
            $pdf = PDF::loadView('pdfs.cdr', $data);
            $pdf->setPaper([0, 0, 612, 936], 'portrait');
            $pdf->setOptions([
                'enable_php' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 12,    // Reduced from 18
                'margin_right' => 12,  // Reduced from 18
                'margin_bottom' => 12, // Reduced from 18
                'margin_left' => 12,   // Reduced from 18
                'dpi' => 150,
                'defaultFont' => 'Arial'
            ]);

            // Generate filename with timestamp
            $filename = sprintf(
                'Cash Disbursement Record:_%s_%s.pdf',
                $cdrID,
                now()->format('Y-m-d_His')
            );

            // Return PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('PDF Export Error', [
                'cdrID' => $cdrID,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred'
            ], 500);
        }
    }
}
