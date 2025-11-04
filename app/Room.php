<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $timestamps = false;
    
    protected $table = 'room'; // or 'rooms' if your table is plural
    protected $fillable = [
        'hotel_id',
        'room_num',
        'li_date',
        'lo_date',
        'survivor_id',
        'archived',
        // add other columns if needed
    ];

    public function hotel()
    {
        return $this->belongsTo(\App\Hotel::class, 'hotel_id');
    }
}