<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $primaryKey = 'provinceID';
    public $incrementing = false;

    protected $fillable = [
        'provinceID',
        'provinceName',
        'totalBeneficiaries',
        'totalAmountReleased'
    ];
}
