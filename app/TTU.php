<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttu';
    public $timestamps = false; // Disable Laravel's automatic timestamps

    protected $fillable = [
        'vin', 'manufacturer', 'brand', 'model', 'year', 'status',
        'title_manufacturer', 'title_brand', 'title_model', 'has_title',
        'location', 'loc_id', 'county', 'imei', 'purchase_price', 'address', 'beds', 'queens', 'bunks',
        'transfer_num', 'author',
        'transpo_agency', 'remarks', 'comments', 'fdec', 'survivor_name',
        'lo', 'lo_date', 'est_lo_date', 'created_at',
        'has_200sqft', 'has_propanefire', 'has_tv', 'has_hydraul',
        'has_steps', 'has_teardrop', 'has_foldwalls', 'has_extkitchen',
        'is_onsite', 'is_occupied', 'is_winterized', 'is_deblocked',
        'is_cleaned', 'is_gps_removed', 'is_beingdonated', 'is_soldatauction'
    ];

    protected $casts = [
        'features' => 'array',
        'statuses' => 'array',
    ];
}
