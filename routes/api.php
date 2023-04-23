<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesmanController;
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

Route::post('login', [AuthController::class, 'login']);

Route::get('/salesman', [SalesmanController::class, 'index'])->name('salesman.list');
Route::post('/salesman', [SalesmanController::class, 'create'])->name('salesman.create');
Route::put('/salesman/{uuid}', [SalesmanController::class, 'update'])->name('salesman.update');
Route::delete('/salesman/{uuid}', [SalesmanController::class, 'destroy'])->name('salesman.destroy');

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('salesman', SalesmanController::class);
});
