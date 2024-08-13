<?php

namespace App\Exceptions;

use Exception;

class SeatNotAvailableException extends Exception
{
    /**
     * Create a new SeatNotAvailableException instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "The selected seat is not available", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {

    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => 'Seat Not Available',
            'message' => $this->getMessage(),
        ], 422);
    }
}
