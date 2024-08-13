<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'trip_id' => $this->trip_id,
            'seat_id' => $this->seat_id,
            'from_city_id' => $this->from_city_id,
            'to_city_id' => $this->to_city_id,
            'created_at' => $this->created_at,
        ];    }
}
