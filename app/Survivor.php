<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $table = 'survivor'; // Specify the table name
    protected $fillable = [
        'fname',         // First Name
        'lname',         // Last Name
        'address',      // Address
        'city',
        'state',
        'zip',
        'primary_phone',        // Phone
        'secondary_phone',        // Phone
        'county',
        'own_rent',
        'hh_size',      // HH Size (Household Size)
        'pets',
        'email',
        'location_type',
        'statepark_name',
        'statepark_site',
        'statepark_li_date',
        'statepark_lo_date',
        'opt_out',
        'opt_out_reason',
        'caseworker_id', // Case Worker ID
        'notes',
        'author',
        'fema_id',
        // Add any other fields present in your form and table
    ];
}
