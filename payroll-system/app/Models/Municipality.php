<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipalityName',
        'municipalityID',
        'provinceID',
        'totalBeneficiaries',
        'totalAmountReleased',
        'claimed',
        'unclaimed',
        'disqualified',
        'double_entry'
        // Add other relevant fields
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function barangay(){
        return $this->hasMany(Barangay::class, 'municipalityID', 'municipalityID');
    }
}
