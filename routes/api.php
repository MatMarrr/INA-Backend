<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneticAlgorithmController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('genetic-algorithm')->group(function () {

    Route::get('/decimal-places/count', [GeneticAlgorithmController::class, 'countDecimalPlaces']);

    Route::prefix('convert')->group(function () {
        Route::get('/real-to-int', [GeneticAlgorithmController::class, 'realToInt']);
        Route::get('/int-to-bin', [GeneticAlgorithmController::class, 'intToBin']);
        Route::get('/bin-to-int', [GeneticAlgorithmController::class, 'binToInt']);
        Route::get('/int-to-real', [GeneticAlgorithmController::class, 'intToReal']);
    });
});

