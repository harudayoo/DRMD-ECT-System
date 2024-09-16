<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masterlist extends Model
{
    protected $table = 'masterlists';
    protected $primaryKey = 'masterlistID';
    public $timestamps = true;

    protected $fillable = [
        'masterlistID',
        'municipalityID',
        'masterlistName',
        'totalAmount',
        'totalBeneficiaries',
        'created_at',
        'updated_at'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipalityID', 'municipalityID');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'masterlistID', 'masterlistID');
    }

}



