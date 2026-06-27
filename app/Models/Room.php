<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['building_id', 'name', 'capacity', 'type'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
