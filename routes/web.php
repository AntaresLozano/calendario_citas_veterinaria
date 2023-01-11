<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/eventos', [EventController::class, 'getEvents']);
Route::post('/create', [EventController::class, 'createEvent']);
Route::delete('/delete/{id}', [EventController::class,'deleteEvent']);
Route::put('/update/{id}', [EventController::class,'updateEvent']);
// Route::delete('/delete/{id}', 'EventController@deleteEvent');