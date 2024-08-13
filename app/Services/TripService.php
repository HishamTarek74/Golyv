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
            $tripAvailableSeats = $this->calculateAvailableSeats($trip, $fromCityId, $toCityId);
            $availableSeats = array_merge($availableSeats, $tripAvailableSeats);

        }

        return $availableSeats;
    }

    private function calculateAvailableSeats($trip, $fromCityId, $toCityId)
    {
        $bookedSeatIds = $this->bookingRepository->getBookedSeatIdsForTrip($trip->id, $fromCityId, $toCityId);

        // Filter available seats
        $availableSeats = array_filter($trip->bus->seats->toArray(), function ($seat) use ($bookedSeatIds) {
            return !in_array($seat['id'], $bookedSeatIds);
        });

        // Add trip_id to the seat data
        return array_map(function ($seat) use ($trip) {
            $seat['trip_id'] = $trip->id;
            return $seat;
        }, $availableSeats);

    }
}
