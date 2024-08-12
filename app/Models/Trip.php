<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['departure_city_id', 'arrival_city_id', 'bus_id'];

    public function departureCity()
    {
        return $this->belongsTo(City::class, 'departure_city_id');
    }

    public function arrivalCity()
    {
        return $this->belongsTo(City::class, 'arrival_city_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
