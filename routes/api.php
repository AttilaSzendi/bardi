<?php

use Illuminate\Support\Facades\Route;

Route::name('api:')->group(function(){
    Route::resource('/seats', 'SeatController')->only(['index', 'store', 'destroy']);
});

Route::name('api:')->group(function(){
    Route::resource('/reservations', 'ReservationController')->only(['store', 'update']);
});
