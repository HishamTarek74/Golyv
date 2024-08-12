<?php

namespace App\Repositories;

use App\Base\Repositories\Repository;
use App\Models\Trip;

class TripRepository extends Repository
{
    public function __construct(Trip $trip)
    {
        $this->setModel($trip);
    }

    public function getAvailableTrips($fromCityId, $toCityId)
    {
        return $this->model::where('departure_city_id', $fromCityId)
            ->where('arrival_city_id', $toCityId)
            ->with(['bus'])
            ->get();
    }


}
