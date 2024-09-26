<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $primaryKey = 'payrollID';

    protected $fillable = [
        'payrollNumber',
        'payrollName',
        'barangayID',
        'subTotal',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'payrollNumber' => 'string',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'barangayID', 'barangayID');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'barangayID');
    }
}
