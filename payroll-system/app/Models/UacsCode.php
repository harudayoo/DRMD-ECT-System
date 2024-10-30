<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UacsCode extends Model
{
    protected $table = 'uacsCodes';
    protected $primaryKey = 'uacsObjectCode';
    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'uacsObjectCode',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function cdr()
    {
        return $this->hasMany(CDR::class, 'uacsCode', 'uacsObjectCode');
    }
}
