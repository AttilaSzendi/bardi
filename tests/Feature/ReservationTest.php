<?php

namespace Tests\Feature;

use App\Reservation;
use App\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function seats_can_be_reserved()
    {
        $seats = factory(Seat::class,2)->create();

        $response = $this->json('post', route('api:reservations.store'), [
            'email' => 'asd@fake.com',
            'selectedSeats' => $seats->pluck('id')->toArray()
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('reservations', [
           'id' => 1
        ]);

        foreach ($seats as $seat) {
            $this->assertDatabaseHas('reservation_seat', [
                'reservation_id' => 1,
                'seat_id' => $seat->id
            ]);
        }
    }

    /**
     * @test
     */
    public function seats_cannot_be_reserved_if_there_is_reservation_in_progress_within_time_period()
    {
        $seat = factory(Seat::class)->create();

        $reservation = factory(Reservation::class)->create(['created_at' => now()]);
        $reservation->seats()->sync([$seat->id]);

        $response = $this->json('post', route('api:reservations.store'), [
            'email' => 'asd@fake.com',
            'selectedSeats' => [$seat->id]
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('reservations', [
            'id' => 2
        ]);
    }

    /**
     * @test
     */
    public function seats_can_be_reserved_if_there_is_reservation_in_progress_but_has_reached_the_time_period()
    {
        $seat = factory(Seat::class)->create();

        $reservation = factory(Reservation::class)->create(['created_at' => now()->addMinutes(3)]);
        $reservation->seats()->sync([$seat->id]);

        $response = $this->json('post', route('api:reservations.store'), [
            'email' => 'asd@fake.com',
            'selectedSeats' => [$seat->id]
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('reservations', [
            'id' => 2
        ]);

        $this->assertDatabaseHas('reservation_seat', [
            'reservation_id' => 2,
            'seat_id' => $seat->id,
        ]);
    }

    /**
     * @test
     */
    public function seats_cannot_be_reserved_if_one_of_the_selected_seat_is_reserved_and_paid()
    {
        $seat = factory(Seat::class)->create();

        $reservation = factory(Reservation::class)->create([
            'created_at' => now()->addMinutes(3),
            'status_id' => Reservation::PAID
        ]);
        $reservation->seats()->sync([$seat->id]);

        $response = $this->json('post', route('api:reservations.store'), [
            'email' => 'asd@fake.com',
            'selectedSeats' => [$seat->id]
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('reservations', [
            'id' => 2
        ]);
    }

    /**
     * @test
     */
    public function reservation_can_be_paid_and_finalized()
    {
        $reservation = factory(Reservation::class)->create(['status_id' => Reservation::RESERVED]);

        $response = $this->json('put', route('api:reservations.update', ['reservation' => $reservation->id]));

        $response->assertOk();

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status_id' => Reservation::PAID
        ]);
    }
}
