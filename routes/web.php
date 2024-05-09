<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\VenuesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\AttendeesRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MyEventsController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'login'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    Route::get('register', [AuthenticatedSessionController::class, 'register'])
        ->name('register');
    Route::post('register', [AuthenticatedSessionController::class, 'create'])
        ->name('register.create');
});

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn () => redirect(route('dashboard')));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UsersController::class);
    Route::resource('venues', VenuesController::class);
    Route::resource('events', EventsController::class);
    Route::delete('/roles/delete/{roleIds}', [RolesController::class, 'deletMultiple'])->name('roles.delete');
    Route::resource('roles', RolesController::class);
    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    // routes/web.php

    Route::get('/attendee/upcoming-events', [AttendeeController::class, 'upcomingEvents'])->name('attendee.upcomingEvents');
    Route::get('/attendee/upcoming-events/{event}', [AttendeeController::class, 'show'])->name('attendee.event.show');
    Route::post('/attendee/upcoming-events/confirm/participate', [AttendeeController::class, 'confirm'])->name('attendee.event.participate.confirm');
    Route::get('/attendee/upcoming-events/participate/{event}', [AttendeeController::class, 'participate'])->name('attendee.event.participate');

    Route::get('attendees/request', [AttendeesRequestController::class, 'index'])->name('attendees.requests');
    Route::post('attendees/request/accept/{attendee}', [AttendeesRequestController::class, 'accept'])->name('attendees.requests.accept');
    Route::get('my-events', [MyEventsController::class, 'index'])->name('my-events');
});
Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');
