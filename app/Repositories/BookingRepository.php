<?php

namespace App\Repositories;

use App\Base\Repositories\Repository;
use App\Models\Booking;

class BookingRepository extends Repository
{
    public function __construct(Booking $booking)
    {
        $this->setModel($booking);
    }

    public function getBookingsForTrip($tripId)
    {
        return $this->model::where('trip_id', $tripId)->get();
    }


}
