<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DvPayroll extends Model
{
    protected $table = 'dvPayrolls';
    protected $primaryKey = 'dvPNumber';
    public $timestamps = true;

    protected $fillable = [
        'dvPNumber',
        'dvPName',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}

