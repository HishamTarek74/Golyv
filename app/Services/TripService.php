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
            }
        }

        return $availableSeats;
    }

    /**
     * Determine if an existing booking overlaps with a requested booking interval.
     *
     * @param \App\Models\Booking $booking The existing booking to check against.
     * @param int $fromCityId The ID of the starting city for the requested booking.
     * @param int $toCityId The ID of the ending city for the requested booking.
     *
     * @return bool Returns true if there is an overlap, false otherwise.
     *
     * @example
     * // Example 1: Overlapping (Scenario 1)
     * // Existing booking: Cairo (1) to AlMinya (3)
     * // Requested booking: AlFayyum (2) to Asyut (4)
     * $booking = new Booking(['from_city_id' => 1, 'to_city_id' => 3]);
     * $result = $this->isOverlappingBooking($booking, 2, 4); // Returns true
     *
     * @example
     * // Example 2: Overlapping (Scenario 2)
     * // Existing booking: AlFayyum (2) to Asyut (4)
     * // Requested booking: Cairo (1) to AlMinya (3)
     * $booking = new Booking(['from_city_id' => 2, 'to_city_id' => 4]);
     * $result = $this->isOverlappingBooking($booking, 1, 3); // Returns true
     *
     * @example
     * // Example 3: Overlapping (Scenario 3)
     * // Existing booking: AlFayyum (2) to AlMinya (3)
     * // Requested booking: Cairo (1) to Asyut (4)
     * $booking = new Booking(['from_city_id' => 2, 'to_city_id' => 3]);
     * $result = $this->isOverlappingBooking($booking, 1, 4); // Returns true
     *
     * @example
     * // Example 4: Not overlapping
     * // Existing booking: Cairo (1) to AlFayyum (2)
     * // Requested booking: AlMinya (3) to Asyut (4)
     * $booking = new Booking(['from_city_id' => 1, 'to_city_id' => 2]);
     * $result = $this->isOverlappingBooking($booking, 3, 4); // Returns false
     *
     * @example
     * // Example 5: Overlapping (same start city)
     * // Existing booking: AlFayyum (2) to Asyut (4)
     * // Requested booking: AlFayyum (2) to AlMinya (3)
     * $booking = new Booking(['from_city_id' => 2, 'to_city_id' => 4]);
     * $result = $this->isOverlappingBooking($booking, 2, 3); // Returns true
     *
     * @example
     * // Example 6: Overlapping (same end city)
     * // Existing booking: Cairo (1) to AlMinya (3)
     * // Requested booking: AlFayyum (2) to AlMinya (3)
     * $booking = new Booking(['from_city_id' => 1, 'to_city_id' => 3]);
     * $result = $this->isOverlappingBooking($booking, 2, 3); // Returns true
     */
    private function isOverlappingBooking($booking, $fromCityId, $toCityId): bool
    {
        return ($booking->from_city_id <= $fromCityId && $booking->to_city_id > $fromCityId) ||
            ($booking->from_city_id < $toCityId && $booking->to_city_id >= $toCityId) ||
            ($booking->from_city_id >= $fromCityId && $booking->to_city_id <= $toCityId);
    }
}
