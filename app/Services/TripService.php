<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use App\Repositories\TripRepository;

class TripService
{
    protected $tripRepository;
    protected $bookingRepository;

    public function __construct(TripRepository $tripRepository, BookingRepository $bookingRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function getAvailableSeats($fromCityId, $toCityId)
    {
        $trips = $this->tripRepository->getAvailableTrips($fromCityId, $toCityId);
        $availableSeats = [];

        foreach ($trips as $trip) {
            $bookings = $this->bookingRepository->getBookingsForTrip($trip->id);
            $availableSeats[$trip->id] = $this->calculateAvailableSeats($trip, $bookings, $fromCityId, $toCityId);
        }

        return $availableSeats;
    }

    private function calculateAvailableSeats($trip, $bookings, $fromCityId, $toCityId)
    {
        $availableSeats = $trip->bus->seats->pluck('id')->toArray();

        foreach ($bookings as $booking) {
            if ($this->isOverlappingBooking($booking, $fromCityId, $toCityId)) {
                $availableSeats = array_diff($availableSeats, [$booking->seat_id]);
                // [1,2,3] [2] => [1,3]
            }
        }

        return $availableSeats;
    }

    private function isOverlappingBooking($booking, $fromCityId, $toCityId)
    {
        return ($booking->from_city_id <= $fromCityId && $booking->to_city_id > $fromCityId) ||
            ($booking->from_city_id < $toCityId && $booking->to_city_id >= $toCityId) ||
            ($booking->from_city_id >= $fromCityId && $booking->to_city_id <= $toCityId);
    }
}
