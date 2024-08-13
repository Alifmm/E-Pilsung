<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::post('/login', [APIController::class, 'apiLogin']);
Route::post('/refresh', [APIController::class, 'refresh']);
Route::middleware('auth:sanctum')->post('/apilogout', [APIController::class, 'apiLogout']);
