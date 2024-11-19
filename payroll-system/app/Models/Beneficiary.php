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

    protected $casts = [
        'dateOfBirth' => 'date',
        'amount' => 'decimal:2',
    ];

    protected $fillable = [
        'beneficiaryID',
        'masterlistID',
        'barangayID',
        'payrollNumber',
        'beneficiaryNumber',
        'lastName',
        'firstName',
        'middleName',
        'extensionName',
        'sex',
        'dateOfBirth',
        'amount',
        'status',


    ];

    public function getSexAttribute($value)
    {
        return $value === 1 ? 'Male' : 'Female';
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'barangayID');
    }

    public function masterlist()
    {
        return $this->belongsTo(Masterlist::class, 'masterlistID', 'masterlistID');
    }

    public static function generateUniqueBeneficiaryNumber($barangayId)
    {
        do {
            $number = mt_rand(0001, 999999); // Generate a random 6-digit number
            $exists = self::where('barangayID', $barangayId)
                ->where('beneficiaryNumber', $number)
                ->exists();
        } while ($exists);

        return $number;
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payrollNumber', 'payrollNumber');
    }
}
