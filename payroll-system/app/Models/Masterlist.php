<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Masterlist extends Model
{
    protected $table = 'masterlists';
    protected $primaryKey = 'masterlistID';
    public $timestamps = true;

    protected $fillable = [
        'masterlistID',
        'municipalityID',
        'masterlistName',
        'totalBeneficiaries',
        'totalAmountReleased',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipalityID', 'municipalityID');
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'masterlistID', 'masterlistID');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'masterlistID', 'masterlistID');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value);
    }
}
