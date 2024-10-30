<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDR extends Model
{
    use HasFactory;

    protected $primaryKey = 'cdrID';
    protected $table = 'cdrs';

    protected $fillable = [
        'cdrID',
        'payrollID',
        'designationID',
        'entityID',
        'uacsObjectCode',
        'dvPNumber',
        'nOPId',
        'cdrName',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payrollID', 'payrollID');
    }

    public function rcd()
    {
        return $this->hasMany(RCD::class, 'cdrID', 'cdrID');
    }

    public function uacsCode()
    {
        return $this->belongsTo(UacsCode::class, 'uacsObjectCode', 'uacsObjectCode');
    }

    public function paymentNature()
    {
        return $this->belongsTo(PaymentNature::class, 'nOPId', 'nOPId');
    }

    public function dvPayroll()
    {
        return $this->belongsTo(DvPayroll::class, 'dvPNumber', 'dvPNumber');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entityID', 'entityID');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designationID', 'designationID');
    }
}
