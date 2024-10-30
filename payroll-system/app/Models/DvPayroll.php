<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DvPayroll extends Model
{
    protected $table = 'dvPayrolls';
    protected $primaryKey = 'dvPNumber';
    public $timestamps = true;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'dvPNumber',
        'check_no',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function cdr()
    {
        return $this->hasMany(CDR::class, 'dvNumber', 'dvPNumber');
    }
}

