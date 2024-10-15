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
        'exportNum',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'payrollNumber' => 'string',
        'subTotal' => 'decimal:2',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'barangayID', 'barangayID');
    }

    public function rcds()
    {
        return $this->hasMany(Rcd::class, foreignKey: 'payrollID', localKey: 'payrollID');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangayID', 'barangayID');
    }

    public function beneficiariesPayroll()
{
    return $this->hasMany(Beneficiary::class, 'payrollNumber', 'payrollNumber');
}
}
