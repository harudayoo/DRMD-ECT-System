<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrsBurs extends Model
{
    protected $table = 'orsBurs';
    protected $primaryKey = 'orsBursNumber';
    public $timestamps = true;

    protected $fillable = [
        'orsBursNumber',
        'created_at',
        'updated_at'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function rcd()
    {
        return $this->hasMany(RCD::class, 'orsNumber', 'orsBursNumber');
    }
}
