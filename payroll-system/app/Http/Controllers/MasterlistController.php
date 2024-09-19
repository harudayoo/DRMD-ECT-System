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

    public function getBeneficiaries($masterlistID)
    {
        try {
            $beneficiaries = Beneficiary::where('masterlistID', $masterlistID)
                ->with('barangay')
                ->get();

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

            // Remove header row
            array_shift($rows);

            // Filter out blank rows
            $rows = array_filter($rows, function ($row) {
                return !empty(array_filter($row));
            });

            $totalBeneficiaries = count($rows);

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

            foreach (array_chunk($rows, $batchSize) as $chunk) {
                foreach ($chunk as $index => $row) {
                    $rowNumber = $processedRows + $index + 2;
                    try {
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
        // Trim all input values to remove leading/trailing whitespace
        $row = array_map('trim', $row);

        $validator = Validator::make([
            'no' => $row[0],
            'lastName' => $row[1],
            'firstName' => $row[2],
            'middleName' => $row[3],
            'ext' => $row[4],
            'sex' => $row[5],
            'dateOfBirth' => $row[6],
            'barangay' => $row[9],
        ], [
            'no' => 'required|integer',
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'ext' => 'nullable|string|max:10',
            'sex' => 'nullable|string|max:10',
            'dateOfBirth' => 'required|string',
            'barangay' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            Log::warning("Row {$rowNumber} validation failed", ['errors' => $errors]);
            throw new \Exception("Row {$rowNumber}: Validation failed: " . implode(", ", $errors));
        }

        $barangayName = $row[9];
        $barangay = $this->findBestMatchingBarangay($barangayName, $masterlist->municipalityID);

        if (!$barangay) {
            Log::warning("Row {$rowNumber}: Barangay not found", ['barangay' => $barangayName]);
            throw new \Exception("Row {$rowNumber}: Barangay not found: {$barangayName}");
        }

        $beneficiaryNumber = Beneficiary::generateUniqueBeneficiaryNumber($barangay->barangayID);

        // Normalize sex field
        $sex = $this->normalizeSex($row[5]);

        // Parse and format date of birth
        $formattedDateOfBirth = $this->parseAndFormatDate($row[6]);
        if (!$formattedDateOfBirth) {
            throw new \Exception("Row {$rowNumber}: Invalid date format for date of birth");
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
            Log::info("Row {$rowNumber}: Existing beneficiary updated", ['beneficiaryId' => $existingBeneficiary->id]);
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
            Log::info("Row {$rowNumber}: New beneficiary added", ['beneficiaryId' => $beneficiary->id]);
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

        // Only return a match if the similarity is above a certain threshold
        return $highestSimilarity > 0.8 ? $bestMatch : null;
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
}
