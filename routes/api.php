<?php

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
        Route::post('/subscribed-members', 'store')->name('api.subscribed-members.store');
    });
