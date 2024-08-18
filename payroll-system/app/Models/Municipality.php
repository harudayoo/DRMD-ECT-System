<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province_id',
        // Add other relevant fields
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
