<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $table = 'survivor'; // Specify the table name
    protected $fillable = [
        'fema_id',      // FEMA-ID
        'fname',         // First Name
        'lname',         // Last Name
        'address',      // Address
        'primary_phone',        // Phone
        'secondary_phone',        // Phone
        'hh_size',      // HH Size (Household Size)
        'li_date',      // LI Date (Last Interaction Date)
    ];
}
