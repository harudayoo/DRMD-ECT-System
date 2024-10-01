<?php

namespace App\Http\Controllers;

use App\Models\Masterlist;
use App\Models\Barangay;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class MasterlistController extends Controller
{
    public function index()
    {
        return Inertia::render('User/ViewMasterlists');
    }

    public function getMasterlists()
    {
        $masterlists = Masterlist::with('municipality')->get();
        return response()->json(['masterlists' => $masterlists]);
    }

    public function update(Request $request, $masterlistID)
    {
        $masterlist = Masterlist::findOrFail($masterlistID);
        $masterlist->update($request->all());
        return response()->json($masterlist);
    }

    public function getBeneficiaries($masterlistID, Request $request)
    {
        try {
            $search = $request->input('search', '');
            $query = Beneficiary::where('masterlistID', $masterlistID)->with('barangay');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('beneficiaryNumber', 'like', "%{$search}%")
                        ->orWhere('firstName', 'like', "%{$search}%")
                        ->orWhere('lastName', 'like', "%{$search}%")
                        ->orWhere('middleName', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $beneficiaries = $query->get();

            return response()->json(['beneficiaries' => $beneficiaries]);
        } catch (\Exception $e) {
            Log::error('Error fetching beneficiaries: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch beneficiaries'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'municipalityID' => 'required|exists:municipalities,municipalityID',
                'masterlistName' => 'required|string|max:255',
            ]);

            $masterlistID = $this->generateMasterlistID();

            $masterlist = Masterlist::create([
                'masterlistID' => $masterlistID,
                'municipalityID' => $validatedData['municipalityID'],
                'masterlistName' => $validatedData['masterlistName'],
                'totalBeneficiaries' => 0,
            ]);

            return response()->json(['message' => 'Masterlist created successfully', 'masterlist' => $masterlist], 201);
        } catch (\Exception $e) {
            Log::error('Error creating masterlist: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create masterlist', 'details' => $e->getMessage()], 500);
        }
    }

    public function getExistingBeneficiaries(Request $request)
    {
        try {
            $municipalityID = $request->query('municipalityID');
            $beneficiaries = Beneficiary::whereHas('barangay', function ($query) use ($municipalityID) {
                $query->where('municipalityID', $municipalityID);
            })->with('barangay')->get();

            return response()->json(['beneficiaries' => $beneficiaries]);
        } catch (\Exception $e) {
            Log::error('Error fetching existing beneficiaries: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch existing beneficiaries'], 500);
        }
    }

    public function addToMasterlist(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'beneficiaryID' => 'required|exists:beneficiaries,id',
                'masterlistID' => 'required|exists:masterlists,masterlistID',
            ]);

            $beneficiary = Beneficiary::findOrFail($validatedData['beneficiaryID']);
            $beneficiary->masterlistID = $validatedData['masterlistID'];
            $beneficiary->save();

            return response()->json(['message' => 'Beneficiary added to masterlist successfully']);
        } catch (\Exception $e) {
            Log::error('Error adding beneficiary to masterlist: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add beneficiary to masterlist'], 500);
        }
    }
    public function import(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv',
                'municipalityId' => 'required|exists:municipalities,municipalityID',
            ]);

            $file = $request->file('file');
            $municipalityId = $request->input('municipalityId');
            Log::info('Starting import process', ['file' => $file->getClientOriginalName(), 'municipalityId' => $municipalityId]);

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip the first 5 rows (headers)
            $dataRows = array_slice($rows, 5);

            // Filter out blank rows
            $dataRows = array_filter($dataRows, function ($row) {
                return !empty(array_filter($row));
            });

            $totalBeneficiaries = count($dataRows);

            DB::beginTransaction();

            $masterlistID = $this->generateMasterlistID();
            $masterlist = Masterlist::create([
                'masterlistID' => $masterlistID,
                'municipalityID' => $municipalityId,
                'masterlistName' => $file->getClientOriginalName(),
                'totalBeneficiaries' => $totalBeneficiaries,
            ]);

            Log::info('Masterlist created', ['masterlistId' => $masterlist->masterlistID, 'totalBeneficiaries' => $totalBeneficiaries]);

            $errors = [];
            $processedRows = 0;
            $batchSize = 100;

            foreach (array_chunk($dataRows, $batchSize) as $chunk) {
                foreach ($chunk as $index => $row) {
                    $rowNumber = $processedRows + $index + 6; // Start from row 6
                    try {
                        Log::debug("Processing row", ['rowNumber' => $rowNumber, 'data' => $row]);
                        $this->processRow($masterlist, $row, $rowNumber);
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    }
                }
                $processedRows += count($chunk);
                DB::commit();
                DB::beginTransaction();
            }

            if (!empty($errors)) {
                DB::rollBack();
                Log::error('Import completed with errors', ['errors' => $errors]);
                return response()->json(['error' => 'Import completed with errors', 'details' => $errors], 422);
            }

            DB::commit();
            Log::info('Import completed successfully');
            return response()->json(['message' => 'Masterlist imported successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during import', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Import failed', 'details' => $e->getMessage()], 500);
        }
    }

    private function processRow(Masterlist $masterlist, array $row, int $rowNumber)
    {
        // Adjust rowNumber to account for header rows
        $actualRowNumber = $rowNumber + 5;

        // Trim all input values to remove leading/trailing whitespace
        $row = array_map('trim', $row);

        // Log the row data for debugging
        Log::debug("Processing row data", [
            'rowNumber' => $rowNumber,
            'lastName' => $row[1] ?? null,
            'firstName' => $row[2] ?? null,
            'middleName' => $row[3] ?? null,
            'barangay' => $row[9] ?? null,
        ]);

        // Validate only if required fields are not empty
        if (empty($row[1]) || empty($row[2]) || empty($row[6]) || empty($row[9])) {
            Log::warning("Row {$rowNumber}: Empty required fields", ['row' => $row]);
            throw new \Exception("Required fields are empty");
        }

        $validator = Validator::make([
            'lastName' => $row[1],
            'firstName' => $row[2],
            'middleName' => $row[3],
            'ext' => $row[4],
            'sex' => $row[5],
            'dateOfBirth' => $row[6],
            'barangay' => $row[9],
        ], [
            'lastName' => 'required|string|max:25',
            'firstName' => 'required|string|max:25',
            'middleName' => 'nullable|string|max:25',
            'ext' => 'nullable|string|max:10',
            'sex' => 'nullable|string|max:10',
            'dateOfBirth' => 'required',
            'barangay' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            Log::warning("Row {$rowNumber} validation failed", ['errors' => $errors]);
            throw new \Exception("Validation failed: " . implode(", ", $errors));
        }

        $barangayName = $row[9];
        $barangay = $this->findBestMatchingBarangay($barangayName, $masterlist->municipalityID);

        if (!$barangay) {
            Log::warning("Row {$rowNumber}: Barangay not found", ['barangay' => $barangayName]);
            throw new \Exception("Barangay not found: {$barangayName}");
        }

        $beneficiaryNumber = Beneficiary::generateUniqueBeneficiaryNumber($barangay->barangayID);

        // Normalize sex field
        $sex = $this->normalizeSex($row[5]);

        // Parse and format date of birth
        $formattedDateOfBirth = $this->parseAndFormatDate($row[6]);
        if (!$formattedDateOfBirth) {
            throw new \Exception("Row {$actualRowNumber}: Invalid date format for date of birth");
        }

        $existingBeneficiary = Beneficiary::where('lastName', $row[1])
            ->where('firstName', $row[2])
            ->where('middleName', $row[3])
            ->where('dateOfBirth', $formattedDateOfBirth)
            ->first();

        if ($existingBeneficiary) {
            $existingBeneficiary->update([
                'status' => 4,
                'masterlistID' => $masterlist->masterlistID,
                'barangayID' => $barangay->barangayID,
                'beneficiaryNumber' => $beneficiaryNumber,
                'extensionName' => $row[4] ?: null,
                'sex' => $sex,
            ]);
            Log::info("Row {$actualRowNumber}: Existing beneficiary updated", ['beneficiaryId' => $existingBeneficiary->id]);
        } else {
            $beneficiary = new Beneficiary([
                'masterlistID' => $masterlist->masterlistID,
                'barangayID' => $barangay->barangayID,
                'beneficiaryNumber' => $beneficiaryNumber,
                'lastName' => $row[1],
                'firstName' => $row[2],
                'middleName' => $row[3] ?: null,
                'extensionName' => $row[4] ?: null,
                'sex' => $sex,
                'dateOfBirth' => $formattedDateOfBirth,
                'status' => 2,
            ]);
            $beneficiary->save();
            Log::info("Row {$actualRowNumber}: New beneficiary added", ['beneficiaryId' => $beneficiary->id]);
        }
    }

    private function findBestMatchingBarangay($barangayName, $municipalityID)
    {
        // Normalize the input barangay name
        $normalizedInput = $this->normalizeBarangayName($barangayName);

        // Get all barangays for the given municipality
        $barangays = Barangay::where('municipalityID', $municipalityID)->get();

        $bestMatch = null;
        $highestSimilarity = 0;

        foreach ($barangays as $barangay) {
            $normalizedBarangay = $this->normalizeBarangayName($barangay->barangayName);

            // Calculate similarity
            $similarity = $this->calculateSimilarity($normalizedInput, $normalizedBarangay);

            // Update best match if this is the highest similarity so far
            if ($similarity > $highestSimilarity) {
                $highestSimilarity = $similarity;
                $bestMatch = $barangay;
            }
        }

        // Reduce the threshold to 0.7 for a more lenient match
        return $highestSimilarity > 0.7 ? $bestMatch : null;
    }

    private function normalizeBarangayName($name)
    {
        // Convert to lowercase and remove common words and punctuation
        $name = strtolower($name);
        $name = preg_replace('/\b(barangay|brgy|bgy|brgy\.)\b/', '', $name);
        $name = preg_replace('/[^\w\s]/', '', $name);
        return trim($name);
    }

    private function calculateSimilarity($str1, $str2)
    {
        // Use Levenshtein distance to calculate similarity
        $levenshtein = levenshtein($str1, $str2);
        $maxLength = max(strlen($str1), strlen($str2));

        // Convert distance to a similarity score (0 to 1)
        return 1 - ($levenshtein / $maxLength);
    }

    private function normalizeSex($sex)
    {
        if (empty($sex)) {
            return null;
        }

        $sex = strtolower($sex);
        if (in_array($sex, ['m', 'male'])) {
            return 'Male';
        } elseif (in_array($sex, ['f', 'female'])) {
            return 'Female';
        }

        return null; // Return null for any other value
    }

    private function parseAndFormatDate($date)
    {
        $formats = [
            'n/j/Y',
            'Y-m-d',
            'm-d-Y',
            'm/d/Y',
            'F j, Y',
        ];

        foreach ($formats as $format) {
            $parsedDate = Carbon::createFromFormat($format, $date, 'UTC');
            if ($parsedDate !== false) {
                return $parsedDate->format('Y-m-d');
            }
        }

        return null;
    }

    private function generateMasterlistID()
    {
        $lastMasterlist = Masterlist::orderBy('masterlistID', 'desc')->first();
        $lastID = $lastMasterlist ? intval($lastMasterlist->masterlistID) : 0;
        $newID = $lastID + 1;
        return str_pad($newID, 6, '0', STR_PAD_LEFT);
    }

    public function export($masterlistID)
    {
        try {
            $masterlist = Masterlist::with([
                'municipality' => function ($query) {
                    $query->with('province');
                },
                'beneficiaries.barangay.municipality.province'
            ])->findOrFail($masterlistID);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->setCellValue('A1', 'Emergency Cash Transfer Masterlist: ' . $masterlist->masterlistName . ', ' . $masterlist->created_at->format('Y-m-d'));
            $worksheet->getStyle('A1')->getFont()->setBold(true);

            // Add masterlist's municipality and province information
            $worksheet->setCellValue('A2', 'Municipality: ' . ($masterlist->municipality ? strtoupper($masterlist->municipality->municipalityName) : 'N/A'));
            $worksheet->setCellValue('A3', 'Province: ' . ($masterlist->municipality && $masterlist->municipality->province ? strtoupper($masterlist->municipality->province->provinceName) : 'N/A'));

            $worksheet->setCellValue('A5', 'No.');
            $worksheet->setCellValue('B5', 'LAST NAME');
            $worksheet->setCellValue('C5', 'FIRST NAME');
            $worksheet->setCellValue('D5', 'MIDDLE NAME');
            $worksheet->setCellValue('E5', 'NAME EXTENSION');
            $worksheet->setCellValue('F5', 'SEX');
            $worksheet->setCellValue('G5', 'DATE OF BIRTH (mm-dd-yyyy)');
            $worksheet->setCellValue('H5', 'CITY/MUNICIPALITY');
            $worksheet->setCellValue('I5', 'PROVINCE');
            $worksheet->setCellValue('J5', 'BARANGAY');

            $worksheet->getStyle('A5:J5')->getFont()->setBold(true);
            $worksheet->getStyle('A5:J5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle('A5:J5')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $row = 6;
            foreach ($masterlist->beneficiaries as $index => $beneficiary) {
                $worksheet->setCellValue('A' . $row, $row - 5);
                $worksheet->setCellValue('B' . $row, strtoupper($beneficiary->lastName));
                $worksheet->setCellValue('C' . $row, strtoupper($beneficiary->firstName));
                $worksheet->setCellValue('D' . $row, strtoupper($beneficiary->middleName));
                $worksheet->setCellValue('E' . $row, strtoupper($beneficiary->extensionName));
                $worksheet->setCellValue('F' . $row, strtoupper($beneficiary->sex));
                $worksheet->setCellValue('G' . $row, $beneficiary->dateOfBirth ? $beneficiary->dateOfBirth->format('m-d-Y') : '');

                if (!$beneficiary->barangay) {
                    Log::warning("Beneficiary {$beneficiary->beneficiaryID} has no barangay", ['beneficiary' => $beneficiary->toArray()]);
                    $worksheet->setCellValue('H' . $row, 'N/A');
                    $worksheet->setCellValue('I' . $row, 'N/A');
                    $worksheet->setCellValue('J' . $row, 'N/A');
                } elseif (!$beneficiary->barangay->municipality) {
                    Log::warning("Barangay {$beneficiary->barangay->barangayID} has no municipality", ['barangay' => $beneficiary->barangay->toArray()]);
                    $worksheet->setCellValue('H' . $row, 'N/A');
                    $worksheet->setCellValue('I' . $row, 'N/A');
                    $worksheet->setCellValue('J' . $row, strtoupper($beneficiary->barangay->barangayName));
                } else {
                    $municipality = $beneficiary->barangay->municipality;
                    $worksheet->setCellValue('H' . $row, strtoupper($municipality->municipalityName));
                    $worksheet->setCellValue('I' . $row, $municipality->province ? strtoupper($municipality->province->provinceName) : 'N/A');
                    $worksheet->setCellValue('J' . $row, strtoupper($beneficiary->barangay->barangayName));
                }

                $worksheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $worksheet->getStyle('A' . $row . ':J' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $row++;
            }

            $worksheet->getColumnDimension('A')->setWidth(10);
            $worksheet->getColumnDimension('B')->setWidth(20);
            $worksheet->getColumnDimension('C')->setWidth(20);
            $worksheet->getColumnDimension('D')->setWidth(20);
            $worksheet->getColumnDimension('E')->setWidth(20);
            $worksheet->getColumnDimension('F')->setWidth(10);
            $worksheet->getColumnDimension('G')->setWidth(20);
            $worksheet->getColumnDimension('H')->setWidth(20);
            $worksheet->getColumnDimension('I')->setWidth(20);
            $worksheet->getColumnDimension('J')->setWidth(20);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $tempFile = tempnam(sys_get_temp_dir(), 'export_');
            $writer->save($tempFile);

            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $masterlist->masterlistName . '.xlsx"',
            ];

            return response()->file($tempFile, $headers)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting masterlist: ' . $e->getMessage(), [
                'masterlistID' => $masterlistID,
                'exception' => $e,
            ]);
            return response()->json(['error' => 'Failed to export masterlist: ' . $e->getMessage()], 500);
        }
    }
}
