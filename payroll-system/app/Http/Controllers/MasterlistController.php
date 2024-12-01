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

    private const SIMILARITY_THRESHOLD = 0.8;

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

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Debug the data structure
        Log::info('Excel import started', [
            'filename' => $file->getClientOriginalName(),
            'total_rows' => count($rows),
            'sample_row' => $rows[5] ?? null
        ]);

        // Skip the first 5 rows (headers)
        $dataRows = array_slice($rows, 5);

        // Add more strict data validation
        $dataRows = array_filter($dataRows, function ($row) {
            // Check if the row has the minimum required data
            return !empty($row[1]) && // Last Name
                   !empty($row[2]) && // First Name
                   !empty($row[6]) && // Date of Birth
                   !empty($row[9]);   // Barangay
        });

        if (empty($dataRows)) {
            throw new \Exception('No valid data rows found in the file.');
        }

        $totalBeneficiaries = count($dataRows);

        DB::beginTransaction();

        try {
            $masterlistID = $this->generateMasterlistID();
            $masterlist = Masterlist::create([
                'masterlistID' => $masterlistID,
                'municipalityID' => $municipalityId,
                'masterlistName' => $file->getClientOriginalName(),
                'totalBeneficiaries' => $totalBeneficiaries,
            ]);

            $errors = [];
            $processedRows = 0;
            $batchSize = 100;
            $similarBeneficiaries = [];

            foreach (array_chunk($dataRows, $batchSize) as $chunk) {
                foreach ($chunk as $index => $row) {
                    try {
                        // Add extra validation before processing
                        if (!$this->isValidRow($row)) {
                            throw new \Exception('Invalid data format');
                        }

                        $rowNumber = $processedRows + $index + 6;
                        $result = $this->processRow($masterlist, $row, $rowNumber);

                        if (!empty($result['similarBeneficiaries'])) {
                            $similarBeneficiaries = array_merge($similarBeneficiaries, $result['similarBeneficiaries']);
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        Log::error("Error processing row {$rowNumber}", [
                            'row_data' => $row,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                $processedRows += count($chunk);
            }

            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Import completed with partial errors',
                    'details' => $errors,
                    'processed_rows' => $processedRows,
                    'total_rows' => $totalBeneficiaries
                ], 422);
            }

            DB::commit();
            return response()->json([
                'message' => 'Masterlist imported successfully',
                'total_beneficiaries' => $processedRows,
                'similarBeneficiaries' => $similarBeneficiaries
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    } catch (\Exception $e) {
        Log::error('Error during import', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'error' => 'Import failed',
            'details' => $e->getMessage()
        ], 500);
    }
}

private function normalizeSex($sex)
{
    if (empty($sex)) {
        return null;
    }

    // Convert to lowercase and trim
    $sex = strtolower(trim($sex));

    // Check for various representations of male
    $maleValues = ['1', 'm', 'male'];

    // Check for various representations of female
    $femaleValues = ['2', 'f', 'female'];

    if (in_array($sex, $maleValues)) {
        return 1; // Male
    } elseif (in_array($sex, $femaleValues)) {
        return 2; // Female
    }

    return null; // Return null for unrecognized values
}

    // Add this new method to validate row structure
    private function isValidRow($row): bool
    {
        // Check if row has all required columns
        if (!is_array($row) || count($row) < 10) {
            return false;
        }

        // Validate data types
        if (!is_string($row[1]) || !is_string($row[2])) { // Last Name and First Name
            return false;
        }

        return true;
    }

    private function processRow(Masterlist $masterlist, array $row, int $rowNumber)
    {
        // Additional logging for debugging
        Log::debug("Full Row Processing", [
            'rowNumber' => $rowNumber,
            'full_row' => $row
        ]);

        // Adjust rowNumber to account for header rows
        $actualRowNumber = $rowNumber;

        // Trim all input values to remove leading/trailing whitespace
        $row = array_map('trim', $row);

        // Validate only if required fields are not empty
        if (empty($row[1]) || empty($row[2]) || empty($row[6]) || empty($row[9])) {
            Log::warning("Row {$actualRowNumber}: Empty required fields", ['row' => $row]);
            throw new \Exception("Required fields are empty");
        }

        // Enhanced validator to be more flexible
        $validator = Validator::make([
            'lastName' => $row[1],
            'firstName' => $row[2],
            'middleName' => $row[3] ?? null,
            'ext' => $row[4] ?? null,
            'sex' => $row[5] ?? null,
            'dateOfBirth' => $row[6],
            'municipality' => $row[7] ?? null,
            'province' => $row[8] ?? null,
            'barangay' => $row[9],
        ], [
            'lastName' => 'required|string|max:25',
            'firstName' => 'required|string|max:25',
            'middleName' => 'nullable|string|max:25',
            'ext' => 'nullable|string|max:10',
            'sex' => 'nullable|string',
            'dateOfBirth' => ['required', function ($attribute, $value, $fail) {
                $parsedDate = $this->parseAndFormatDate($value);
                if ($parsedDate === null) {
                    $fail('The date of birth is not a valid date.');
                }
            }],
            'barangay' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            Log::warning("Row {$actualRowNumber} validation failed", ['errors' => $errors, 'row' => $row]);
            throw new \Exception("Validation failed: " . implode(", ", $errors));
        }

        $barangayName = $row[9];
        $barangay = $this->findBestMatchingBarangay($barangayName, $masterlist->municipalityID);

        if (!$barangay) {
            Log::warning("Row {$actualRowNumber}: Barangay not found", [
                'barangay' => $barangayName,
                'municipalityID' => $masterlist->municipalityID
            ]);
            throw new \Exception("Barangay not found: {$barangayName}");
        }

        $beneficiaryNumber = Beneficiary::generateUniqueBeneficiaryNumber($barangay->barangayID);

        // More robust sex normalization
        $sex = $this->normalizeSex($row[5]);

        // Improve date parsing
        $formattedDateOfBirth = $this->parseAndFormatDate($row[6]);

        if (!$formattedDateOfBirth) {
            Log::warning("Row {$actualRowNumber}: Invalid date format", ['date' => $row[6]]);
            throw new \Exception("Invalid date format: {$row[6]}");
        }

        $similarBeneficiaries = [];
        $potentialDuplicates = $this->findPotentialDuplicates(
            $row[2],   // firstName
            $row[1],   // lastName
            $row[3],   // middleName
            $formattedDateOfBirth
        );

        // If duplicates exist, add to similar beneficiaries array
        if ($potentialDuplicates->isNotEmpty()) {
            foreach ($potentialDuplicates as $duplicate) {
                $similarityScore = $this->calculateSimilarity(
                    "{$duplicate->lastName} {$duplicate->firstName}",
                    "{$row[1]} {$row[2]}"
                );

                $similarBeneficiaries[] = [
                    'imported' => [
                        'firstName' => $row[2],
                        'lastName' => $row[1],
                        'middleName' => $row[3] ?? null,
                        'dateOfBirth' => $formattedDateOfBirth
                    ],
                    'existing' => [
                        'firstName' => $duplicate->firstName,
                        'lastName' => $duplicate->lastName,
                        'middleName' => $duplicate->middleName,
                        'dateOfBirth' => $duplicate->dateOfBirth
                    ],
                    'similarityScore' => round($similarityScore * 100, 2),
                    'action' => 'potential_duplicate'
                ];
            }
        }

        // Create new beneficiary record
        $beneficiary = new Beneficiary([
            'masterlistID' => $masterlist->masterlistID,
            'barangayID' => $barangay->barangayID,
            'beneficiaryNumber' => $beneficiaryNumber,
            'lastName' => $row[1],
            'firstName' => $row[2],
            'middleName' => $row[3] ?? null,
            'extensionName' => $row[4] ?? null,
            'sex' => $sex, // This will now be 1, 2, or null
            'dateOfBirth' => $formattedDateOfBirth,
            'status' => 2 // Default status
        ]);

        $beneficiary->save();

        return [
            'similarBeneficiaries' => $similarBeneficiaries,
            'beneficiary' => $beneficiary
        ];
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
        if (empty($str1) || empty($str2)) {
            return 0;
        }

        // Normalize strings
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));

        // Use Levenshtein distance to calculate similarity
        $levenshtein = levenshtein($str1, $str2);
        $maxLength = max(strlen($str1), strlen($str2));

        // Convert distance to a similarity score (0 to 1)
        return 1 - ($levenshtein / $maxLength);
    }

    private function findPotentialDuplicates($firstName, $lastName, $middleName, $dateOfBirth)
    {
        // First, look for exact matches
        $query = Beneficiary::where(function ($q) use ($firstName, $lastName, $middleName, $dateOfBirth) {
            $q->where('dateOfBirth', $dateOfBirth)
                ->where(DB::raw('LOWER(lastName)'), strtolower($lastName));
        });

        $exactMatches = $query->get();

        // If no exact matches, look for similar names
        if ($exactMatches->isEmpty()) {
            return Beneficiary::where('dateOfBirth', $dateOfBirth)
                ->get()
                ->filter(function ($beneficiary) use ($firstName, $lastName, $middleName) {
                    $lastNameSimilarity = $this->calculateSimilarity($beneficiary->lastName, $lastName);
                    $firstNameSimilarity = $this->calculateSimilarity($beneficiary->firstName, $firstName);

                    // Consider middle name if both records have it
                    $middleNameSimilarity = 1.0;
                    if (!empty($middleName) && !empty($beneficiary->middleName)) {
                        $middleNameSimilarity = $this->calculateSimilarity($beneficiary->middleName, $middleName);
                    }

                    // Calculate weighted average similarity
                    $overallSimilarity = ($lastNameSimilarity * 0.4) + ($firstNameSimilarity * 0.4) + ($middleNameSimilarity * 0.2);

                    return $overallSimilarity >= self::SIMILARITY_THRESHOLD;
                });
        }

        return $exactMatches;
    }

    private function parseAndFormatDate($date)
    {
        $formats = [
            'm-d-Y', // Explicitly handle MM-DD-YYYY
            'n/j/Y',
            'Y-m-d',
            'm/d/Y',
            'd-m-Y',
            'd/m/Y',
            'F j, Y',
            'Y-m-d H:i:s',
            'Y/m/d',
            'd-M-Y'
        ];

        foreach ($formats as $format) {
            try {
                $parsedDate = Carbon::createFromFormat($format, $date, 'UTC');
                if ($parsedDate !== false) {
                    return $parsedDate->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Continue to next format if parsing fails
                continue;
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

                $worksheet->setCellValue('F' . $row, $this->sexToText($beneficiary->sex));
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

    private function sexToText($sex)
    {
        return match ($sex) {
            '1' => 'MALE',
            '2' => 'FEMALE',
            default => ''
        };
    }
}
