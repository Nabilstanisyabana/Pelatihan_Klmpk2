<?php

use App\Http\Controllers\GisController;
use App\Http\Controllers\DepotController;
use Illuminate\Support\Facades\Route;

// Route untuk Halaman Utama Peta dan Endpoint API GeoJSON
Route::get('/', [GisController::class, 'index']);
Route::get('/api/geojson/gadm/{level}', [GisController::class, 'getGadmGeoJson'])->where('level', '[0-4]');

// Route API CRUD Depot & Lokasi (Tugas Mandiri Halaman 7 Modul GIS)
Route::get('/api/depots', [DepotController::class, 'index']);
Route::post('/api/depots', [DepotController::class, 'store']);
Route::put('/api/depots/{id}', [DepotController::class, 'update']);
Route::delete('/api/depots/{id}', [DepotController::class, 'destroy']);
Route::post('/api/depots/reset-sample', [DepotController::class, 'resetSample']);
