<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\User;

class FleetManagementSeeder extends Seeder
{
    public function run()
    {
        //create test user

        User::create([
            'name' => 'Hisham',
            'email' => 'hesham@gmail.com',
            'password' => 'password'
        ]);
        // Create specific cities
        $cities = [
            ['name' => 'Cairo'],
            ['name' => 'AlFayyum'],
            ['name' => 'AlMinya'],
            ['name' => 'Asyut'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }

        // Create buses and seats
        Bus::factory(5)->create()->each(function ($bus) {
            for ($i = 1; $i <= $bus->total_seats; $i++) {
                Seat::create([
                    'bus_id' => $bus->id,
                    'seat_number' => $i,
                ]);
            }
        });

        // Create trips
        Trip::factory(10)->create();

        // Create users
        User::factory(20)->create();

        // Create bookings based on examples
        $this->createExampleBookings();
    }

    private function createExampleBookings()
    {
        $cairo = City::where('name', 'Cairo')->first();
        $alFayyum = City::where('name', 'AlFayyum')->first();
        $alMinya = City::where('name', 'AlMinya')->first();
        $asyut = City::where('name', 'Asyut')->first();

        $trip1 = Trip::factory()->create([
            'departure_city_id' => $cairo->id,
            'arrival_city_id' => $asyut->id,
        ]);

        $trip2 = Trip::factory()->create([
            'departure_city_id' => $cairo->id,
            'arrival_city_id' => $asyut->id,
        ]);

        $seat = Seat::inRandomOrder()->first();

        // Example 1: Cairo (1) to AlMinya (3)
        Booking::factory()->create([
            'user_id' => User::factory(),
            'seat_id' => $seat->id,
            'trip_id' => $trip1->id,
            'from_city_id' => $cairo->id,
            'to_city_id' => $alMinya->id,
        ]);

        // Example 2: AlFayyum (2) to Asyut (4)
        Booking::factory()->create([
            'user_id' => User::factory(),
            'seat_id' => $seat->id,
            'trip_id' => $trip2->id,
            'from_city_id' => $alFayyum->id,
            'to_city_id' => $asyut->id,
        ]);

        // Example 3: Cairo (1) to AlFayyum (2)
//        Booking::factory()->create([
//            'user_id' => User::factory(),
//            'trip_id' => $trip2->id,
//            'from_city_id' => $cairo->id,
//            'to_city_id' => $alFayyum->id,
//        ]);
//
//        // Example 4: AlFayyum (2) to AlMinya (3)
//        Booking::factory()->create([
//            'user_id' => User::factory(),
//            'trip_id' => $trip2->id,
//            'from_city_id' => $alFayyum->id,
//            'to_city_id' => $alMinya->id,
//        ]);
//
//
//
//        // Example 5: AlFayyum (2) to Asyut (4)
//        Booking::factory()->create([
//            'user_id' => User::factory(),
//            'trip_id' => $trip2->id,
//            'from_city_id' => $alFayyum->id,
//            'to_city_id' => $asyut->id,
//        ]);
//
//        // Example 6: Cairo (1) to AlMinya (3)
//        Booking::factory()->create([
//            'user_id' => User::factory(),
//            'trip_id' => $trip2->id,
//            'from_city_id' => $cairo->id,
//            'to_city_id' => $alMinya->id,
//        ]);
    }
}
