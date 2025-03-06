<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
})->name(('api.user.find'));

Route::controller(App\Http\Controllers\SubscriptionController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/subscriptions/{gymId}', 'index')->name('api.subscriptions.index')->middleware('role:admin|owner');
        Route::get('/subscriptions/{gymId}/ci/{ci}', 'show')->name('api.subscriptions.show')->middleware('role:admin|owner');
        Route::post('/subscriptions/{gymId}', 'store')->name('api.subscriptions.store')->middleware('role:admin|owner');
    });

Route::controller(App\Http\Controllers\MemberController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/members', 'index')->name('api.members.index');
        Route::get('/members/ci/{ci}', 'findOneByCi')->name('api.members.find');
        Route::post('/members', 'store')->name('api.members.store');
    });

Route::controller(App\Http\Controllers\EntryController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/entries', 'index')->name('api.entries.index');
        Route::post('/entries', 'store')->name('api.entries.store');
    });

Route::controller(App\Http\Controllers\MembershipController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/memberships', 'index')->name('api.memberships.index');
        Route::post('/memberships', 'store')->name('api.memberships.store');
    });

Route::controller(App\Http\Controllers\GymController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/gyms', 'index')->name('api.gyms.index')->middleware('role:admin|owner');
        Route::post('/gyms', 'store')->name('api.gyms.store')->middleware('role_or_permission:create gyms');
    });
