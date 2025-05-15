<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TTU extends Model
{
    protected $table = 'ttus';
    protected $fillable = [
        'vin',
        'manufacturer',
        'brand',
        'model',
        'year',
        'status',
        'location',
        'unit',
        'county',
        'imei',
        'purchase_price',
        'disposition',
        'transport_agency',
        'recipient_type',
        'agency',
        'donation_category',
        'remarks',
        'comments',
        'fema_id',
        'name',
        'lo',
        'lo_date',
        'est_lo_date',
    ];
    
    // Disable timestamps
    public $timestamps = false;
}
