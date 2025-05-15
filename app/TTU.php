<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttus';
    protected $fillable = ['vin', 'location', 'address', 'unit', 'status', 'total_beds'];
    
    // Disable timestamps
    public $timestamps = false;
}
