<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $primaryKey = 'designationID';

    protected $table = 'designations';

    protected $fillable = [

        'designationID',
        'officialDesignation',
        'accountableOfficer',
        'station',
        'created_at',
        'updated_at',

    ];

    public function cdr()
    {
        return $this->hasMany(CDR::class, 'designationID', 'designationID');
    }
}
