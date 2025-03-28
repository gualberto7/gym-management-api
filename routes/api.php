<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
})->name(('api.user.find'));

Route::controller(App\Http\Controllers\UserController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/me', 'index');
});

Route::controller(App\Http\Controllers\SubscriptionController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/gym/{gymId}/subscriptions', 'index')->name('api.subscriptions.index')->middleware('role:admin|owner');
        Route::get('/gym/{gymId}/subscriptions/member/{ci}', 'show')->name('api.subscriptions.show')->middleware('role:admin|owner');
        Route::post('/gym/{gymId}/subscriptions', 'store')->name('api.subscriptions.store')->middleware('role:admin|owner');
    });

Route::controller(App\Http\Controllers\MemberController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/members', 'index')->name('api.members.index');
        Route::get('/members/ci/{ci}', 'findOneByCi')->name('api.members.find');
        Route::post('/members', 'store')->name('api.members.store');
    });

Route::controller(App\Http\Controllers\EntryController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/gym/{gymId}/entries', 'index')->name('api.entries.index')->middleware('role:admin|owner');;
        Route::post('/gym/{gymId}/entries', 'store')->name('api.entries.store')->middleware('role:admin|owner');
    });

Route::controller(App\Http\Controllers\MembershipController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/gym/{gymId}/memberships', 'index')->name('api.memberships.index')->middleware('role:admin|owner');
        Route::post('/gym/{gymId}/memberships', 'store')->name('api.memberships.store')
            ->middleware('role_or_permission:create memberships');
    Route::put('/gym/{gymId}/memberships/{membership}', 'update')->name('api.memberships.update')
        ->middleware('role_or_permission:update memberships');
    });

Route::controller(App\Http\Controllers\GymController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/gyms', 'index')->name('api.gyms.index')->middleware('role:admin|owner');
        Route::post('/gyms', 'store')->name('api.gyms.store')->middleware('role_or_permission:create gyms');
        Route::put('/gyms/{gym}', 'update')->name('api.gyms.update')->middleware('role_or_permission:create gyms');
    });
