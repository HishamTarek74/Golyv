<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AvailableSeatsRequest;
use App\Http\Resources\AvailableSeatsResource;
use App\Http\Resources\SeatResource;
use App\Services\TripService;

class TripController
{
    protected $tripService;

    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
    }

    public function getAvailableSeats(AvailableSeatsRequest $request)
    {
        $availableSeats = $this->tripService->getAvailableSeats(
            $request->from_city_id,
            $request->to_city_id
        );

        return AvailableSeatsResource::collection($availableSeats);
    }
}
