<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MonasteryController;
use App\Http\Controllers\Api\KtitorController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\AiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', fn () => response()->json(['ok' => true]));

// === MANASTIRI ===
Route::get('/monasteries/regions', [MonasteryController::class, 'regions']);
Route::get('/monasteries/cities', [MonasteryController::class, 'cities']);
Route::get('/monasteries-map', [MonasteryController::class, 'map']);

Route::get('/monasteries', [MonasteryController::class, 'index']);
Route::get('/monasteries/{slug}', [MonasteryController::class, 'show']);

// === KTITORI ===
Route::get('/ktitors', [KtitorController::class, 'index']);
Route::get('/ktitors/{slug}', [KtitorController::class, 'show']);

// === CONTENT ===
Route::get('/content/categories', [ContentController::class, 'categories']);
Route::get('/content/{categorySlug}/items', [ContentController::class, 'items']);
Route::get('/content/{categorySlug}/items/{itemSlug}', [ContentController::class, 'show']);

// === AI ===
Route::post('/ai/chat', [AiController::class, 'chat']);