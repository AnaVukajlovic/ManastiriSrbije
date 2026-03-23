<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\MapController;
use App\Http\Controllers\MapAiController;
use App\Http\Controllers\MonasteryController;
use App\Http\Controllers\KtitorController;

use App\Http\Controllers\PravoslavniController;
use App\Http\Controllers\PravoslavniCalendarController;

use App\Http\Controllers\CuriosityController;
use App\Http\Controllers\VaskrsController;
use App\Http\Controllers\EdukacijaController;
use App\Http\Controllers\AiController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Mapa
|--------------------------------------------------------------------------
*/
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/map/{slug}', [MapController::class, 'show'])->name('map.show');
Route::get('/mapa', fn () => redirect()->route('map.index'));

/*
|--------------------------------------------------------------------------
| Pravoslavni sadržaj
|--------------------------------------------------------------------------
*/
Route::get('/pravoslavni', [PravoslavniController::class, 'index'])->name('pravoslavni.index');

/* Kalendar */
Route::get('/pravoslavni/kalendar', [PravoslavniCalendarController::class, 'index'])
    ->name('pravoslavni.kalendar.index');

Route::get('/pravoslavni/kalendar/{date}', [PravoslavniCalendarController::class, 'show'])
    ->where('date', '\d{4}-\d{2}-\d{2}')
    ->name('pravoslavni.kalendar.show');

/* Osnovni koncepti */
Route::get('/pravoslavni/osnovni-koncepti', function () {
    return view('pages.pravoslavni.modules.osnovni-koncepti');
})->name('pravoslavni.osnovni-koncepti');

/*
|--------------------------------------------------------------------------
| Moduli pod /pravoslavni/*
|--------------------------------------------------------------------------
*/
Route::prefix('pravoslavni')->group(function () {

    /* Zanimljivosti */
    Route::get('/zanimljivosti', [CuriosityController::class, 'index'])
        ->name('curiosities.index');

    Route::get('/zanimljivosti/{slug}', [CuriosityController::class, 'show'])
        ->name('curiosities.show');

    /* Datum Vaskrsa */
    Route::get('/datum-vaskrsa', [VaskrsController::class, 'index'])
        ->name('vaskrs.index');

    Route::get('/datum-vaskrsa/{slug}', [VaskrsController::class, 'show'])
        ->name('vaskrs.show');

    /* Edukacija: specifične rute prvo */
    Route::get('/edukacija/ucenje-interakcija', [EdukacijaController::class, 'ucenjeInterakcija'])
        ->name('edukacija.ucenje-interakcija');

    Route::get('/edukacija/porodicno-stablo', [EdukacijaController::class, 'porodicnoStablo'])
        ->name('edukacija.porodicno-stablo');

    Route::get('/edukacija/timeline', [EdukacijaController::class, 'timeline'])
        ->name('edukacija.timeline');

    Route::get('/edukacija/quiz-history', [EdukacijaController::class, 'quizHistory'])
        ->name('edukacija.quiz-history');

    Route::post('/edukacija/quiz-history', [EdukacijaController::class, 'quizHistorySubmit'])
        ->name('edukacija.quiz-history.submit');

    Route::get('/edukacija/quiz-orthodox', [EdukacijaController::class, 'quizOrthodox'])
        ->name('edukacija.quiz-orthodox');

    Route::post('/edukacija/quiz-orthodox', [EdukacijaController::class, 'quizOrthodoxSubmit'])
        ->name('edukacija.quiz-orthodox.submit');

    Route::get('/edukacija/ai', [EdukacijaController::class, 'ai'])
        ->name('edukacija.ai');

    Route::post('/edukacija/ai/chat', [EdukacijaController::class, 'aiChat'])
        ->name('edukacija.ai.chat');

    Route::post('/ai/timeline', [AiController::class, 'chat'])
        ->name('ai.timeline');

    /* Edukacija index + slug na kraju */
    Route::get('/edukacija', [EdukacijaController::class, 'index'])
        ->name('edukacija.index');

    Route::get('/edukacija/{slug}', [EdukacijaController::class, 'show'])
        ->name('edukacija.show');
});

/*
|--------------------------------------------------------------------------
| Redirect starih /edukacija/* URL-ova
|--------------------------------------------------------------------------
*/
Route::get('/edukacija', fn () => redirect()->route('edukacija.index'));
Route::get('/edukacija/{any}', fn () => redirect()->route('edukacija.index'))
    ->where('any', '.*');

/*
|--------------------------------------------------------------------------
| Generički slug za /pravoslavni/{slug} - UVEK na kraju pravoslavnih ruta
|--------------------------------------------------------------------------
*/
Route::get('/pravoslavni/{slug}', [PravoslavniController::class, 'show'])
    ->name('pravoslavni.show');

/*
|--------------------------------------------------------------------------
| Statične stranice
|--------------------------------------------------------------------------
*/
Route::view('/ture', 'pages.tours')->name('tours.index');
Route::view('/ai', 'pages.ai')->name('ai.index');

/*
|--------------------------------------------------------------------------
| Alias rute
|--------------------------------------------------------------------------
*/
Route::get('/map-alias', fn () => redirect()->route('map.index'))->name('map');
Route::get('/ai-alias', fn () => redirect()->route('ai.index'))->name('ai');

/*
|--------------------------------------------------------------------------
| Manastiri
|--------------------------------------------------------------------------
*/
Route::get('/manastiri', [MonasteryController::class, 'index'])->name('monasteries.index');
Route::get('/manastiri/{slug}', [MonasteryController::class, 'show'])->name('monasteries.show');

/*
|--------------------------------------------------------------------------
| Ktitori
|--------------------------------------------------------------------------
*/
Route::prefix('ktitori')->name('ktitors.')->group(function () {
    Route::get('/', [KtitorController::class, 'index'])->name('index');
    Route::get('/{slug}', [KtitorController::class, 'show'])->name('show');
    Route::post('/{slug}/ask-ai', [KtitorController::class, 'askAi'])->name('askAi');
});

/*
|--------------------------------------------------------------------------
| Dashboard redirect
|--------------------------------------------------------------------------
*/
Route::redirect('/dashboard', '/');

/*
|--------------------------------------------------------------------------
| AI za mapu
|--------------------------------------------------------------------------
*/
Route::post('/map/ai/recommend-by-city', [MapAiController::class, 'recommendByCity'])
    ->name('map.ai.recommendByCity');