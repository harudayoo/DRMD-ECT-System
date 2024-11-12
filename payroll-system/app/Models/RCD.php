<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RCD extends Model
{
    use HasFactory;

    protected $primaryKey = 'rcdID';
    protected $table = 'rcds';

    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [

        'rcdID',
        'rcdName',
        'cdrID',
        'orsNumber',
        'responCode',


    ];

    public function CDR()
    {
        return $this->belongsTo(CDR::class, 'cdrID');
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
