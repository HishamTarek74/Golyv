<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        $trip = Trip::inRandomOrder()->first();
        $fromCityId = $trip->departure_city_id;
        $toCityId = $trip->arrival_city_id;
        $seat = Seat::where('bus_id', $trip->bus_id)->inRandomOrder()->first();

        return [
//            'user_id' => User::factory(),
//            'trip_id' => $trip->id,
//            'seat_id' => $seat->id,
//            'from_city_id' => $fromCityId,
//            'to_city_id' => $toCityId,
        ];
    }
}
