<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;
    protected $primaryKey = 'entityID';

    protected $table = 'entities';

    public $incrementing = true;

    protected $fillable = [

        'entityID',
        'entityName',
        'fundCluster',
        'created_at',
        'updated_at',

    ];

    public function cdr()
    {
        return $this->hasMany(CDR::class, 'entityID', 'entityID');
    }
}
