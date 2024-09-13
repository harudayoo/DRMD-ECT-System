<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masterlist extends Model
{
    protected $table = 'masterlists';
    protected $primaryKey = 'masterlistID';
    public $timestamps = true;

    protected $fillable = [
        'barangayID',
        'masterlistName',
        'totalAmount',
        'totalBeneficiaries',
        'created_at',
        'updated_at'
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'barangayID');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'masterlistID', 'masterlistID');
    }

}



