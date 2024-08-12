<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function departureTrips()
    {
        return $this->hasMany(Trip::class, 'departure_city_id');
    }

    public function arrivalTrips()
    {
        return $this->hasMany(Trip::class, 'arrival_city_id');
    }
}
