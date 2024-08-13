<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\City;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{

    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departureCityId = City::inRandomOrder()->first()->id;
        $arrivalCityId = City::where('id', '!=', $departureCityId)->inRandomOrder()->first()->id;

        return [
            'departure_city_id' => $departureCityId,
            'arrival_city_id' => $arrivalCityId,
            'bus_id' => Bus::factory(),
            'departure_time' => $this->faker->dateTimeBetween('+1 day', '+1 week'),
            'arrival_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
        ];
    }
}
