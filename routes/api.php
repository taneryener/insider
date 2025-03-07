<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FixtureController;
use App\Http\Controllers\Api\TeamController;

Route::prefix('/api')->group(function () {
    Route::get('/teams', [TeamController::class, 'all'])->name('teams');

    Route::post('/fixture/play', [FixtureController::class, 'play'])->name('fixture.play');
    Route::post('/fixture/play-all', [FixtureController::class, 'playAll'])->name('fixture.play-all');

    Route::post('/fixture/create', [FixtureController::class, 'create'])->name('fixture.create');
    Route::delete('/fixture/delete', [FixtureController::class, 'delete'])->name('fixture.delete');

    Route::get('/fixture', [FixtureController::class, 'fixture'])->name('fixture');
    Route::get('/fixture/next-match', [FixtureController::class, 'nextMatch'])->name('fixture.next-match');
    Route::get('/fixture/weeks', [FixtureController::class, 'weeks'])->name('fixture.weeks');
});
