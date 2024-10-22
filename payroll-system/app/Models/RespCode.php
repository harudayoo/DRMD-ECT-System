<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespCode extends Model
{
    protected $table = 'respcodes';
    protected $primaryKey = 'responsibilityCode';
    public $timestamps = true;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'responsibilityCode',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function rcd()
    {
        return $this->hasMany(RCD::class, 'responCode', 'responsibilityCode');
    }
}
