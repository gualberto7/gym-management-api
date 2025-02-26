<?php

use App\Http\Controllers\ChenkisController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\SubscribedMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
})->name(('api.user.find'));

Route::controller(SubscribedMemberController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/subscribed-members', 'index')->name('api.subscribed-members.index');
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

Route::controller(ChenkisController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/chenkis', 'index')->name('api.chenkis.index');
        Route::post('/chenkis', 'store')->name('api.chenkis.store');
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
        Route::get('/gyms', 'index')->name('api.gyms.index');
    });
