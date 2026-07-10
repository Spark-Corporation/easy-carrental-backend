<?php
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoricController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PanneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories',CategoryController::class);
Route::apiResource('drivers',DriverController::class);
Route::apiResource('pannes',PanneController::class);
Route::apiResource('cars',CarController::class);
Route::apiResource('historics',HistoricController::class);
Route::apiResource('users',UserController::class);
Route::apiResource('reservations', ReservationController::class);
Route::apiResource('invoices',InvoiceController::class);


