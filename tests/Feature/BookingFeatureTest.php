<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\City;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function testBookSeatSuccessfully()
    {
        // Seed the database with necessary data or use factories
        $user = User::factory()->create();
        $fromCity = City::factory()->create();
        $toCity = City::factory()->create();
        $bus = Bus::factory()->create();
        $trip = Trip::factory()->create([
            'bus_id' => $bus->id,
            'departure_city_id' => $fromCity->id,
            'arrival_city_id' => $toCity->id,
        ]);
        $seat = Seat::create(['bus_id' => $bus->id, 'seat_number' => 10]);


        // Prepare the request data
        $data = [
            'trip_id' => $trip->id,
            'seat_id' => $seat->id,
            'from_city_id' => $fromCity->id,
            'to_city_id' => $toCity->id,
        ];

        // Hit the bookSeat endpoint
        $response = $this->actingAs($user)->postJson('/api/bookings', $data);

        // Assert the response status and structure
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                    'id', 'user_id', 'trip_id', 'seat_id', 'from_city_id', 'to_city_id'
                ],
        ]);

        // Assert the booking was created in the database
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_id' => $seat->id,
            'from_city_id' => $fromCity->id,
            'to_city_id' => $toCity->id,
        ]);
    }

    public function testBookSeatFailsWhenSeatNotAvailable()
    {
        // Seed the database with necessary data
        $user = User::factory()->create();
        $fromCity = City::factory()->create();
        $toCity = City::factory()->create();
        $bus = Bus::factory()->create();
        $trip = Trip::factory()->create(); // trip not same from city to city
        $seat = Seat::factory()->create();

        // Assuming seat ID 999 is not available
        $data = [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seat_id' => $seat->id,  // Non-existent or unavailable seat
            'from_city_id' => $fromCity->id,
            'to_city_id' => $toCity->id,
        ];

        // Hit the bookSeat endpoint
        $response = $this->actingAs($user)->postJson('/api/bookings', $data);

        // Assert the response status and error structure
        $response->assertStatus(422); // Assuming validation failure
        $response->assertJson([
            'error' => 'The selected seat is not available for booking.',
        ]);
    }
}

