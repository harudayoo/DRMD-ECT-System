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
        'cdrID',
        'rcdName',
        'orsNumber',
        'responCode',
    ];

    protected $casts = [
        'rcdID' => 'integer',
        'cdrID' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function cdr()
    {
        return $this->belongsTo(CDR::class, 'cdrID', 'cdrID');
    }

    public function orsBurs()
    {
        return $this->belongsTo(OrsBurs::class, 'orsNumber', 'orsBursNumber');
    }

    public function respCode()
    {
        return $this->belongsTo(RespCode::class, 'responCode', 'responsibilityCode');
    }
}
