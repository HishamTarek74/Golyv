<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Seat;
use App\Models\City;
use App\Models\Booking;

class AvailableSeatsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAvailableSeatsSuccessfully()
    {
        // Seed the database with necessary data
        $fromCity = City::factory()->create();
        $toCity = City::factory()->create();
        $bus = Bus::factory()->create();
        $trip = Trip::factory()->create(['bus_id' => $bus->id, 'departure_city_id' => $fromCity->id, 'arrival_city_id' => $toCity->id]);

        // Create seats for the bus
        $seat1 = Seat::factory()->create(['bus_id' => $bus->id]);
        $seat2 = Seat::factory()->create(['bus_id' => $bus->id]);

        // Create a booking for one of the seats
        Booking::factory()->create([
            'user_id' => User::factory()->create(),
            'trip_id' => $trip->id,
            'seat_id' => $seat1->id,
            'from_city_id' => $fromCity->id,
            'to_city_id' => $toCity->id,
        ]);

        // Hit the get available seats endpoint
        $response = $this->getJson("/api/trips/available-seats?from_city_id={$fromCity->id}&to_city_id={$toCity->id}");

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'trip_id',
                    'seat_number', // assuming there's a 'number' or 'seat_number' field
                ]
            ]
        ]);

        // Assert the correct seats are returned
        $responseData = $response->json('data');
        $this->assertCount(1, $responseData);
        $this->assertEquals($seat2->id, $responseData[0]['id']);
    }

    public function testGetAvailableSeatsWithNoSeatsAvailable()
    {
        // Seed the database with necessary data
        $fromCity = City::factory()->create();
        $toCity = City::factory()->create();
        $bus = Bus::factory()->create();
        $trip = Trip::factory()->create(['bus_id' => $bus->id, 'departure_city_id' => $fromCity->id, 'arrival_city_id' => $toCity->id]);

        // Create seats for the bus
        $seat1 = Seat::factory()->create(['bus_id' => $bus->id]);

        // Book all seats
        Booking::factory()->create([
            'user_id' => User::factory()->create(),
            'trip_id' => $trip->id,
            'seat_id' => $seat1->id,
            'from_city_id' => $fromCity->id,
            'to_city_id' => $toCity->id,
        ]);

        // Hit the get available seats endpoint
        $response = $this->getJson("/api/trips/available-seats?from_city_id={$fromCity->id}&to_city_id={$toCity->id}");

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        // Assert no seats are returned
        $this->assertEmpty($response->json('data'));
    }
}

