<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttus'; // Specify the table name
    protected $fillable = ['vin', 'location', 'address', 'unit', 'status', 'total_beds']; // Add relevant columns
}
