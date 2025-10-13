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
        'transfer_num', 'author', 'condition_code',
        'transpo_agency', 'remarks', 'comments', 'fdec_id', 'survivor_name',
        'li_date', 'lo_date', 'est_lo_date', 'created_at',
        'has_200sqft', 'has_propanefire', 'has_tv', 'has_hydraul',
        'has_steps', 'has_teardrop', 'has_foldwalls', 'has_extkitchen',
        'is_onsite', 'is_occupied', 'is_winterized', 'is_deblocked',
        'is_cleaned', 'is_gps_removed', 'is_beingdonated', 'is_soldatauction',
        'location', 'location_type', 'survivor_id', 'disposition',
        'weather_radio', 'key_no'
    ];

    protected $casts = [
        'features' => 'array',
        'statuses' => 'array',
        'fdec_id'  => 'array',
    ];
}
