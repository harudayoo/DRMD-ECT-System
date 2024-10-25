<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RCD extends Model
{
    use HasFactory;

    protected $primaryKey = 'rcdID';
    protected $table = 'rcds';
    protected $fillable = [

        'rcdID',
        'rcdName',
        'cdrID',
        'dvNumber',
        'orsNumber',
        'responCode',
        'uacsCode',
        'paymentNature'


    ];

    public function CDR()
    {
        return $this->belongsTo(CDR::class, 'cdrID');
    }

    public function orsBurs()
    {
        return $this->belongsTo(OrsBurs::class, 'orsNumber', 'orsBursNumber');
    }

    public function respCode()
    {
        return $this->belongsTo(RespCode::class, 'responCode', 'responsibilityCode');
    }

    public function uacsCode()
    {
        return $this->belongsTo(UacsCode::class, 'uacsCode', 'uacsObjectCode');
    }

    public function paymentNature()
    {
        return $this->belongsTo(PaymentNature::class, 'paymentNature', 'natureOfPayment');
    }

}
