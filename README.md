# Golyv Application

Welcome to the Golyv application, a Laravel-based project for managing trips and seat bookings.

## Table of Contents

- [Installation](#installation)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
    - [Get Available Seats](#get-available-seats)
    - [Book a Seat](#book-a-seat)

## Installation

### Prerequisites

Ensure you have the following installed:

- **Docker**: [Download Docker](https://www.docker.com/products/docker-desktop)
- **WSL (Windows Subsystem for Linux)**: [Install WSL](https://docs.microsoft.com/en-us/windows/wsl/install)
- **Composer**: [Install Composer](https://getcomposer.org/download/)

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/golyv.git
   cd golyv
   composer install
   cp .env.example .env

2. ** Update Database Configuration**

   ```bash
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=golyve
    DB_USERNAME=sail
    DB_PASSWORD=password

3. ** Running app using sail Once Sail is up and running, you can access  http://localhost:80.**

   ```bash
    php artisan sail:install
    ./vendor/bin/sail up -d
    ./vendor/bin/sail artisan migrate --seed
    ./vendor/bin/sail test


4. **To stop Sail**
   ```bash
   ./vendor/bin/sail down

## Endpoints

### Get Available Seats

**Endpoint:** `GET /api/trips/available-seats`

**Description:** Retrieve a list of available seats for a specific trip based on the departure and destination cities.

**Request Parameters:**

- `from_city_id` (integer, required): ID of the departure city.
- `to_city_id` (integer, required): ID of the destination city.


4. **Request Example**
   ```bash
   curl -X GET "http://localhost/api/trips/available-seats?from_city_id=1&to_city_id=3"

### Booking a seat

**Endpoint:** POST /api/bookings

**Description:** user can book a seat based in from & to city if it available.
- Bearer Auth Token Required -> you can get from /api/login

**Request Body:**

- from_city_id (integer, required): ID of the departure city.
- to_city_id (integer, required): ID of the destination city.
- seat_id  (integer, required): ID of the seat id.
- trip_id  (integer, required): ID of the trip id.
  *Request Example:*
```bash
 curl -X GET "http://localhost/api/bookings"




