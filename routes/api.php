<?php

use App\Http\Controllers\Api\AreasController;
use Illuminate\Support\Facades\Route;

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
//
Route::prefix('area')->group(function () {
    Route::post('/create', [AreasController::class, 'create']);
    Route::delete('/delete/{area}', [AreasController::class, 'delete']);
});
