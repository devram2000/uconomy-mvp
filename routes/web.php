<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NexmoSMSController;
use App\Http\Livewire\MakeTransaction;
use App\Http\Livewire\AddIdentity;
use App\Http\Livewire\Waitlist;
use App\Http\Livewire\UserView;
use App\Http\Livewire\Admin;
use App\Http\Livewire\PaymentComponent;
use App\Http\Livewire\RescheduleComponent;
use App\Http\Controllers\UPayController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AutoAddressController;
 
 

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
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->get('/home', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/foo', function () {
    Artisan::call('storage:link');
});


Route::get('/email/verify', function () {
    return view('auth.email-verify');
});

Route::get('/phone/verify', [App\Http\Controllers\NexmoController::class, 'show'])
    ->middleware(['auth:'.config('fortify.guard')]);

Route::post('/phone/verify', [App\Http\Controllers\NexmoController::class, 'verify'])
    ->middleware(['auth:'.config('fortify.guard'), 'throttle:6,1']);

Route::post('/reset', [App\Http\Controllers\NexmoController::class, 'reset'])
    ->name('reset');
    
Route::get('/identity/verify', AddIdentity::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('identity');

Route::get('transact', MakeTransaction::class, 'index')
    ->middleware(['auth:'.config('fortify.guard')])
    ->middleware('validated')
    ->name('transact');

Route::post('transactAjax', [MakeTransaction::class, 'ajax'])
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('transactAjax');

Route::get('admin', Admin::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->middleware('admin')
    ->name('admin');

Route::get('/admin-user/{id}', UserView::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->middleware('admin')
    ->name('userView'); 

Route::get('waitlist', Waitlist::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->middleware('admin')
    ->name('waitlist');

Route::get('payment', PaymentComponent::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('payment');

Route::get('reschedule', RescheduleComponent::class, 'render')
    ->middleware(['auth:'.config('fortify.guard')])
    ->name('reschedule');


