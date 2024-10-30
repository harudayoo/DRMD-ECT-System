<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentNature extends Model
{
    protected $table = 'paymentsNature';
    protected $primaryKey = 'nOPId';
    public $timestamps = true;

    protected $fillable = [
        'nOPId',
        'natureOfPayment',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function cdr()
    {
        return $this->hasMany(CDR::class, 'nOPId', 'nOPId');
    }
}
