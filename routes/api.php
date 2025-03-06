<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FixtureController;
use App\Http\Controllers\Api\TeamController;

Route::get('/api/teams', [TeamController::class, 'all'])->name('teams');
Route::get('/api/fixture', [FixtureController::class, 'fixture'])->name('fixture');
Route::post('/api/fixture/create', [FixtureController::class, 'create'])->name('fixture.create');
