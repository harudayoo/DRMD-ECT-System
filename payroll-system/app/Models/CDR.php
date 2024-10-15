<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDR extends Model
{
    use HasFactory;

    protected $primaryKey = 'cdrID';
    protected $table = 'cdrs';

    protected $fillable = [

        'cdrID',
        'cdrName',
        'created_at',
        'updated_at',

    ];

}
