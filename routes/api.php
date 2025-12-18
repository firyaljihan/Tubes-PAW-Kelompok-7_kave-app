<?php

use App\Http\Controllers\Api\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\EventController;

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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'registerMahasiswa']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/categories', [KategoriController::class, 'index']);

    Route::prefix('admin')->group(function () {
        Route::post('/create-penyelenggara', [AdminController::class, 'createPenyelenggara']);
        Route::post('/kategori', [KategoriController::class, 'store']);
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
    });

    Route::prefix('events')->group(function () {
        Route::get('/getAllEvent', [EventController::class, 'index']);
        Route::post('/addEvent', [EventController::class, 'store']);     // Tambah
        Route::get('/event/{id}', [EventController::class, 'show']);   // Detail
        Route::post('/updateEvent/{id}', [EventController::class, 'update']);
        Route::delete('/event/{id}', [EventController::class, 'destroy']); // Hapus
    });
});

