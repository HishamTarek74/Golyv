<?php

namespace App\Http\Controllers\API;

use App\Exceptions\SeatNotAvailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookSeatRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function bookSeat(BookSeatRequest $request)
    {
        try {
            $booking = $this->bookingService->bookSeat(
                auth()->id(),
                $request->trip_id,
                $request->seat_id,
                $request->from_city_id,
                $request->to_city_id
            );

            return new BookingResource($booking);
        } catch (SeatNotAvailableException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
