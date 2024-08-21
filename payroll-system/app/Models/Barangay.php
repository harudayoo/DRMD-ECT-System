<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangayID',
        'barangayName',
        'municipalityID',
        'totalBeneficiaries',
        'totalAmountReleased',
        'created_at',
        'updated_at',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipalityID', 'municipalityID');
    }
}
