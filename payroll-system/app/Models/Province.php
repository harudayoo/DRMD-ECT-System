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
        'totalAmountReleased',
        'claimed',
        'unclaimed',
        'disqualified',
        'double_entry'
    ];

    public function municipalities()
{
    return $this->hasMany(Municipality::class, 'provinceID', 'provinceID');
    }
}


