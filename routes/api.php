<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoricController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarBackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SettingController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Routes publiques
Route::post('register', [AuthController::class, 'register']);
Route::post('loginAdmin', [AuthController::class, 'loginAdmin']);
Route::post('loginClient', [AuthController::class, 'loginClient']);
Route::post('/reservations/estimate', [ReservationController::class, 'estimate']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// Routes réservées aux clients connectés
Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/reservations/myReservations', [ReservationController::class, 'myReservations']);
    Route::put('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']); // ses factures uniquement
});

// Routes accessibles à tout utilisateur connecté (admin OU client)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn(Request $r) => $r->user());
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('payments',[ PaymentController::class,'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/drivers/available', [DriverController::class, 'availableDrivers']);
    Route::get('/cars/available', [CarController::class, 'availableCars']);
    Route::apiResource('categories', CategoryController::class)->except(['index']);
    Route::apiResource('drivers', DriverController::class);
    Route::apiResource('pannes', PanneController::class);
    // Route::apiResource('cars', CarController::class)->except(['index', 'show']);
    Route::put('/cars/{car}', [CarController::class, 'update']);
    Route::post('/cars', [CarController::class, 'store']);
    Route::delete('/cars/{car}', [CarController::class, 'destroy']);
    Route::apiResource('historics', HistoricController::class);


    Route::apiResource('users', UserController::class);
    Route::apiResource('invoices', InvoiceController::class)->except(['show']);
    Route::apiResource('payments', PaymentController::class)->except(['store']);
    Route::get('/statistics', [StatisticsController::class, 'index']);
    Route::get('/statistics/topCars', [StatisticsController::class, 'topCars']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/settings/{key}', [SettingController::class, 'show']);
    Route::put('/settings/{key}', [SettingController::class, 'update']);
});

Route::get('/cars/{car}', [CarController::class, 'show']);
Route::apiResource('carbacks', CarBackController::class);
