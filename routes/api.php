<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//api routes /api/***

Route::get('/league', function () {
    return response()->json(['message' => 'league response']);
});
