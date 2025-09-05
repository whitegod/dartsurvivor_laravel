<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{
    protected $table = 'survivor'; // Specify the table name
    protected $fillable = [
        'fname',
        'lname',
        'address',
        'city',
        'state',
        'zip',
        'fdec_id',
        'primary_phone',
        'secondary_phone',
        'county',
        'own_rent',
        'hh_size',
        'pets',
        'li_date',
        'email',
        'location_type',
        'statepark_name',
        'statepark_site',
        'statepark_li_date',
        'statepark_lo_date',
        'opt_out',
        'opt_out_reason',
        'caseworker_id',
        'notes',
        'author',
        'fema_id',
        'group0_2', 'group3_6', 'group7_12', 'group13_17',
        'group18_21', 'group22_65', 'group65plus'
        // Add any other fields present in your form and table
    ];
}
