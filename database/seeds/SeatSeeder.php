<?php

use App\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run()
    {
        factory(Seat::class, 20)->create();
    }
}
