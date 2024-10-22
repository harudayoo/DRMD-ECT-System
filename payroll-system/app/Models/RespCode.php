<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespCode extends Model
{
    protected $table = 'respCodes';
    protected $primaryKey = 'responsibilityCode';
    public $timestamps = true;

    protected $fillable = [
        'responsibilityCode',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
