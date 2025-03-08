<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FixtureController;
use App\Http\Controllers\Api\TeamController;

Route::get('/teams', [TeamController::class, 'all'])->name('teams');
Route::get('/teams/points', [TeamController::class, 'points'])->name('teams.points');

Route::post('/fixture/play', [FixtureController::class, 'playNextWeek'])->name('fixture.play');
Route::post('/fixture/play-all', [FixtureController::class, 'playAll'])->name('fixture.play-all');

Route::post('/fixture/create', [FixtureController::class, 'create'])->name('fixture.create');
Route::delete('/fixture/delete', [FixtureController::class, 'delete'])->name('fixture.delete');

Route::get('/fixture', [FixtureController::class, 'fixture'])->name('fixture');
Route::get('/fixture/next-matches', [FixtureController::class, 'nextMatches'])->name('fixture.next-match');
Route::get('/fixture/weeks', [FixtureController::class, 'weeks'])->name('fixture.weeks');
Route::get('/fixture/predictions', [FixtureController::class, 'predictions'])->name('fixture.predictions');
