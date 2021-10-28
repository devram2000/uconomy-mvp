<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NexmoSMSController;
use App\Http\Livewire\MakeTransaction;
use App\Http\Controllers\UPayController;
use App\Http\Controllers\CalendarController;
 
 

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
    return redirect('/home');
});

Route::get('/dashboard', function () {
    return redirect('/home');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/home', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/email/verify', function () {
    return view('auth.email-verify');
});



// Route::group(['middleware' => 'auth'], function() {
//     Route::get('register-step2', 
//         [\App\Http\Controllers\RegisterStepTwoController::class, 'create'])
//         ->name('register-step2.create');
//     Route::post('register-step2', 
//         [\App\Http\Controllers\RegisterStepTwoController::class, 'store'])
//         ->name('register-step2.post');

// });

// Route::get('/verify', 'VerifyController@show')->name('verify');
// Route::post('/verify', 'VerifyController@verify')->name('verify');


// Route::get('/phone', [App\Http\Controllers\NexmoController::class, 'showStart'])
//     ->middleware(['auth:'.config('fortify.guard')])
//     ->name('nexmo');

Route::get('/phone/verify', [App\Http\Controllers\NexmoController::class, 'show'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('nexmo');

Route::post('/phone/verify', [App\Http\Controllers\NexmoController::class, 'verify'])
    ->middleware(['auth:'.config('fortify.guard'), 'throttle:6,1'])
    ->name('nexmo');

Route::post('/reset', [App\Http\Controllers\NexmoController::class, 'reset'])
    ->name('reset');
    

// Route::get('/nexmo', 'NexmoController@show')->name('nexmo');
// Route::post('/nexmo', 'NexmoController@verify')->name('nexmo');

// Route::group(['middleware' => 'auth'], function() {

// });


Route::get('transact', MakeTransaction::class)
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('transact');


Route::get('upay', [UPayController::class, 'index'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('upay');

Route::post('upayAjax', [UPayController::class, 'ajax'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('upayAjax');



Route::get('calendar', [CalendarController::class, 'index'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('calendar');

Route::post('calendarAjax', [CalendarController::class, 'ajax'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('calendarAjax');


