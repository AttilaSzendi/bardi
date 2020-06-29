<?php

namespace Tests\Feature;

use App\Seat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function seats_can_be_retrieved()
    {
        $seats = factory(Seat::class,3)->create();

        $response = $this->get(route('api:seats.index'));

        $response->assertOk();

        $response->assertJsonCount($seats->count(), 'data');
    }

    /**
     * @test
     */
    public function seats_can_be_stored()
    {
        $response = $this->post(route('api:seats.store'));

        $response->assertCreated();

        $this->assertDatabaseHas('seats', [
           'id' => 1
        ]);
    }

    /**
     * @test
     */
    public function seats_can_be_destroyed()
    {
        $seat = factory(Seat::class)->create();

        $response = $this->delete(route('api:seats.destroy', ['seat' => $seat->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('seats', [
            'id' => $seat->id
        ]);
    }
}
