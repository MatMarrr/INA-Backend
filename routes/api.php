<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneticAlgorithmController;
use App\Http\Middleware\CheckCustomAuthKey;
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
Route::middleware([CheckCustomAuthKey::class])->group(function () {

    Route::prefix('genetic-algorithm')->group(function () {

        Route::get('/random-float/generate', [GeneticAlgorithmController::class, 'generateRandomFloat']);

        Route::get('/decimal-places/force', [GeneticAlgorithmController::class, 'forceDecimalPlaces']);

        Route::get('/decimal-places/count', [GeneticAlgorithmController::class, 'countDecimalPlaces']);
        Route::get('/l/count', [GeneticAlgorithmController::class, 'countL']);
        Route::get('/function-value/count', [GeneticAlgorithmController::class, 'countFunctionValue']);

        Route::prefix('convert')->group(function () {
            Route::get('/real-to-int', [GeneticAlgorithmController::class, 'realToInt']);
            Route::get('/int-to-bin', [GeneticAlgorithmController::class, 'intToBin']);
            Route::get('/bin-to-int', [GeneticAlgorithmController::class, 'binToInt']);
            Route::get('/int-to-real', [GeneticAlgorithmController::class, 'intToReal']);
        });

        Route::get('/all-conversions-table', [GeneticAlgorithmController::class, 'allConversionsTable']);
        Route::get('/fx-table', [GeneticAlgorithmController::class, 'fXTable']);
        Route::get('/fx-gx-table', [GeneticAlgorithmController::class, 'fXGxTable']);
        Route::get('/fx-gx-pi-table', [GeneticAlgorithmController::class, 'fXGxPiTable']);
        Route::get('/fx-gx-pi-qi-table', [GeneticAlgorithmController::class, 'fxGxPiQiTable']);
        Route::get('/fx-gx-pi-qi-r-table', [GeneticAlgorithmController::class, 'fxGxPiQiRTable']);
        Route::get('/fx-gx-pi-qi-r-x-table', [GeneticAlgorithmController::class, 'fxGxPiQiRXTable']);
    });
});
