<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationSeatTable extends Migration
{
    public function up()
    {
        Schema::create('reservation_seat', function (Blueprint $table) {
            $table->unsignedBigInteger('reservation_id')->index();
            $table->unsignedBigInteger('seat_id')->index();
            $table->timestamps();

            $table->foreign('reservation_id')
                ->references('id')
                ->on('reservations')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('seat_id')
                ->references('id')
                ->on('seats')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation_seat');
    }
}
