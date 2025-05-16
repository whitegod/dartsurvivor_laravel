<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttus';
    protected $fillable = [
        'vin', 'manufacturer', 'brand', 'model', 'year', 'status',
        'title_manufacturer', 'title_brand', 'title_model', 'has_title',
        'location', 'lot', 'county', 'imei', 'price', 'address', 'total_beds',
        'features', 'statuses', 'disposition', 'transport', 'recipient_type',
        'agency', 'category', 'remarks', 'comments', 'fema', 'survivor_name',
        'lo', 'lo_date', 'est_lo_date', 'created_at', 'updated_at'
    ];
    
    protected $casts = [
        'features' => 'array',
        'statuses' => 'array',
    ];

    // Disable timestamps
    public $timestamps = false;
}
