<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    public $timestamps = false;

    protected $table = 'hotel'; // or 'hotels' if your table is plural
    
    protected $fillable = [
        'name',
        // add other columns if needed
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }
}