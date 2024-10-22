<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UacsCode extends Model
{
    protected $table = 'uacsCodes';
    protected $primaryKey = 'uacsObjectCode';
    public $timestamps = true;

    protected $fillable = [
        'uacsObjectCode',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
