<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $table = 'survivors'; // Specify the table name
    protected $fillable = [
        'fema_id',      // FEMA-ID
        'name',         // Name
        'address',      // Address
        'phone',        // Phone
        'hh_size',      // HH Size (Household Size)
        'li_date',      // LI Date (Last Interaction Date)
    ];
}
