<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statepark extends Model
{
    public $timestamps = false;

    protected $table = 'statepark';

    protected $fillable = [
        'name',
        // add other columns if needed
    ];

    public function lodgeUnits()
    {
        return $this->hasMany(LodgeUnit::class, 'statepark_id');
    }
}