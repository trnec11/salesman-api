<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeListController;
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
Route::get('codelists', [CodeListController::class, 'index'])->middleware('auth:sanctum');

Route::get('salesman', [SalesmanController::class, 'index'])->name('salesman.list')->middleware('auth:sanctum');
Route::post('salesman', [SalesmanController::class, 'store'])->name('salesman.create')->middleware('auth:sanctum');
Route::put('salesman', [SalesmanController::class, 'update'])->name('salesman.update')->middleware('auth:sanctum');
Route::delete('salesman', [SalesmanController::class, 'destroy'])->name('salesman.delete')->middleware('auth:sanctum');
