<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;


    const SEATS_PER_BUS = 12;

    protected $fillable = ['name'];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
