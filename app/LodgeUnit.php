<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LodgeUnit extends Model
{
    public $timestamps = false;

    protected $table = 'lodge_unit';

    protected $fillable = [
        'statepark_id',
        'unit_name',
        'li_date',
        'lo_date',
        'survivor_id',
        // add other columns if needed
    ];

    public function statepark()
    {
        return $this->belongsTo(Statepark::class, 'statepark_id');
    }
}