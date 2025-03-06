<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
})->name(('api.user.find'));

Route::controller(SubscriptionController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/subscriptions/{gymId}', 'index')->name('api.subscriptions.index')->middleware('role:admin|owner');
        Route::get('/subscribed-members/{ci}', 'show')->name('api.subscribed-members.show');
        Route::post('/subscribed-members', 'store')->name('api.subscribed-members.store');
    });

Route::controller(MemberController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/members', 'index')->name('api.members.index');
        Route::get('/members/ci/{ci}', 'findOneByCi')->name('api.members.find');
        Route::post('/members', 'store')->name('api.members.store');
    });

Route::controller(EntryController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/entries', 'index')->name('api.entries.index');
        Route::post('/entries', 'store')->name('api.entries.store');
    });

Route::controller(MembershipController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/memberships', 'index')->name('api.memberships.index');
        Route::post('/memberships', 'store')->name('api.memberships.store');
    });

Route::controller(GymController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/gyms', 'index')->name('api.gyms.index')->middleware('role:admin|owner');
        Route::post('/gyms', 'store')->name('api.gyms.store')->middleware('role_or_permission:create gyms');
    });
