<?php

namespace App\Services;

use App\Exceptions\SeatNotAvailableException;
use App\Repositories\BookingRepository;
use App\Repositories\TripRepository;
use Illuminate\Validation\ValidationException;

class BookingService
{
    protected $bookingRepository;
    protected $tripRepository;
    protected $tripService;

    public function __construct(
        BookingRepository $bookingRepository,
        TripRepository    $tripRepository,
        TripService       $tripService
    )
    {
        $this->bookingRepository = $bookingRepository;
        $this->tripRepository = $tripRepository;
        $this->tripService = $tripService;
    }

    public function bookSeat($userId, $tripId, $seatId, $fromCityId, $toCityId)
    {
        $this->tripRepository->find($tripId);
        $availableSeats = $this->tripService->getAvailableSeats($fromCityId, $toCityId);

        if (!in_array($seatId, $availableSeats[$tripId])) {
            throw new SeatNotAvailableException("The selected seat is not available for booking.");

        }

        return $this->bookingRepository->create([
            'user_id' => $userId,
            'trip_id' => $tripId,
            'seat_id' => $seatId,
            'from_city_id' => $fromCityId,
            'to_city_id' => $toCityId,
        ]);
    }
}
