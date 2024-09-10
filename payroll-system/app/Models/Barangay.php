<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $table = 'barangays';
    protected $primaryKey = 'barangayID';

    protected $fillable = [
        'barangayID',
        'barangayName',
        'municipalityID',
        'totalBeneficiaries',
        'totalAmountReleased',
        'claimed',
        'unclaimed',
        'disqualified',
        'double_entry',
        'created_at',
        'updated_at',
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipalityID', 'municipalityID');
    }

    public function masterlist()
    {
        return $this->hasMany(Masterlist::class, 'barangayID', 'barangayID');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'barangayID', 'barangayID');
    }
}
