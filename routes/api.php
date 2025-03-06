<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;

Route::get('/api/teams', [TeamController::class, 'all'])->name('teams');
