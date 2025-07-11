<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttu';

    protected $fillable = [
        'vin', 'manufacturer', 'brand', 'model', 'year', 'status',
        'title_manufacturer', 'title_brand', 'title_model', 'has_title',
        'loc_id', 'county', 'imei', 'li_date', 'purchase_price', 'beds', 'queens', 'bunks',
        'transfer_num', 'author',
        'transpo_agency', 'remarks', 'comments', 'fdec', 'survivor_name',
        'lo', 'lo_date', 'est_lo_date', 'created_at',
        'has_200sqft', 'has_propanefire', 'has_tv', 'has_hydraul',
        'has_steps', 'has_teardrop', 'has_foldwalls', 'has_extkitchen',
        'is_onsite', 'is_occupied', 'is_winterized', 'is_deblocked',
        'is_cleaned', 'is_gps_removed', 'is_beingdonated', 'is_soldatauction',
        'location', 'location_type',
    ];

    protected $casts = [
        'features' => 'array',
        'statuses' => 'array',
    ];
}
