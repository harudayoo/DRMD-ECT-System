<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\BeneficiaryUpdated;

class Beneficiary extends Model
{
    use HasFactory;

    protected $table = 'beneficiaries';
    protected $primaryKey = 'beneficiaryID';
    public $timestamps = true;

    protected $dispatchesEvents = [
        'created' => BeneficiaryUpdated::class,
        'updated' => BeneficiaryUpdated::class,
    ];

    protected $fillable = [
        'barangayID',
        'beneficiaryNumber',
        'lastName',
        'firstName',
        'middleName',
        'extensionName',
        'dateOfBirth',
        'amount',
        'status',

    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'barangayID');
    }
    public static function generateUniqueBeneficiaryNumber($barangayId)
{
    do {
        $number = mt_rand(1000, 9999); // Generate a random 4-digit number
        $exists = self::where('barangayID', $barangayId)
                     ->where('beneficiaryNumber', $number)
                     ->exists();
    } while ($exists);

    return $number;
}

}
