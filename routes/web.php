<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NexmoSMSController;
use App\Http\Livewire\ProductController;
 
 

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
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
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

Route::get('/phone/verify', [App\Http\Controllers\NexmoController::class, 'show'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('nexmo');

Route::post('/phone/verify', [App\Http\Controllers\NexmoController::class, 'verify'])
    ->middleware(['auth:'.config('fortify.guard'), 'throttle:6,1'])
    ->name('nexmo');
    

// Route::get('/nexmo', 'NexmoController@show')->name('nexmo');
// Route::post('/nexmo', 'NexmoController@verify')->name('nexmo');

// Route::group(['middleware' => 'auth'], function() {

// });

Route::get('products', ProductController::class);
