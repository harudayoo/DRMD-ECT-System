<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RCD extends Model
{
    use HasFactory;

    protected $primaryKey = 'rcdID';
    protected $table = 'rcds';
    protected $fillable = [

        'rcdID',
        'rcdName',
        'payrollID',
        'dvNumber',
        'orsNumber',
        'responCode',
        'uacsCode',
        'paymentNature'


    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payrollID');
    }


}
