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


    /**
     * Retrieve the IDs of seats that are booked for a specific trip and overlap with the requested route.
     *
     * This method checks for existing bookings on a given trip that may overlap with a new booking request.
     * It returns the seat IDs that are currently booked and would conflict with the new booking based on
     * three overlap conditions:
     * 1. The existing booking starts before or at the requested start city and ends after the requested start city.
     * 2. The existing booking starts before the requested end city and ends at or after the requested end city.
     * 3. The existing booking is entirely within the requested route.
     *
     * @param int $tripId The ID of the trip to check bookings for.
     * @param int $fromCityId The ID of the starting city for the requested booking.
     * @param int $toCityId The ID of the ending city for the requested booking.
     *
     * @return array An array of seat IDs that are already booked and overlap with the requested route.
     *
     * @example
     * // Assuming trip ID is 6, with existing bookings overlapping the route from city ID 1 to city ID 4.
     * $seatIds = $this->getBookedSeatIdsForTrip(6, 1, 4);
     * // Returns an array of seat IDs that are booked and would conflict with the new booking.
     */
    public function getBookedSeatIdsForTrip($tripId, $fromCityId, $toCityId)
    {
        return $this->model::where('trip_id', $tripId)
            ->where(function ($query) use ($fromCityId, $toCityId) {
                $query->where(function ($q) use ($fromCityId) {
                    $q->where('from_city_id', '<=', $fromCityId)
                        ->where('to_city_id', '>', $fromCityId);
                })->orWhere(function ($q) use ($toCityId) {
                    $q->where('from_city_id', '<', $toCityId)
                        ->where('to_city_id', '>=', $toCityId);
                })->orWhere(function ($q) use ($fromCityId, $toCityId) {
                    $q->where('from_city_id', '>=', $fromCityId)
                        ->where('to_city_id', '<=', $toCityId);
                });
            })
            ->pluck('seat_id')
            ->toArray();
    }


}
