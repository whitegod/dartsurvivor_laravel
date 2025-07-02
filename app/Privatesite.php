<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privatesite extends Model
{
    protected $table = 'privatesite'; // if your table is not 'privatesites'
    protected $fillable = [
        'ttu_id', 'name', 'address', 'phone', 'pow', 'h2o', 'sew', 'own', 'res',
        'damage_assessment', 'ehp', 'ehp_notes', 'dow_long', 'dow_lat', 'zon', 'dow_response'
    ];
}
