<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/solutions', function () {
    return view('solutions');
})->name('solutions');

Route::get('/work', function () {
    return view('work');
})->name('work');

Route::get('/tickets', function () {
    return view('tickets');
})->name('tickets');

Route::post('/tickets', [EventBookingController::class, 'store'])->name('tickets.store');

Route::get('/insights', function () {
    return view('insights');
})->name('insights');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
