<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;

use App\Http\Controllers\MapController;
use App\Http\Controllers\MonasteryController;
use App\Http\Controllers\KtitorController;

use App\Http\Controllers\PravoslavniController;
use App\Http\Controllers\PravoslavniCalendarController;

use App\Http\Controllers\CuriosityController;
use App\Http\Controllers\VaskrsController;
use App\Http\Controllers\EdukacijaController;

use App\Http\Controllers\Admin\MonasteryReviewController;
use App\Http\Controllers\Admin\ImportReviewController;

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
Route::get('/mapa', fn () => redirect()->route('map.index')); // alias bez name

/*
|--------------------------------------------------------------------------
| Pravoslavni sadržaj (index + specifične rute)
| VAŽNO: specifične rute moraju biti PRE /pravoslavni/{slug}
|--------------------------------------------------------------------------
*/
Route::get('/pravoslavni', [PravoslavniController::class, 'index'])->name('pravoslavni.index');

/* KALENDAR (index + show) */
Route::get('/pravoslavni/kalendar', [PravoslavniCalendarController::class, 'index'])
    ->name('pravoslavni.kalendar.index');

Route::get('/pravoslavni/kalendar/{date}', [PravoslavniCalendarController::class, 'show'])
    ->where('date', '\d{4}-\d{2}-\d{2}')
    ->name('pravoslavni.kalendar.show');

/* Osnovni koncepti (statičan view) */
Route::get('/pravoslavni/osnovni-koncepti', function () {
    return view('pages.pravoslavni.modules.osnovni-koncepti');
})->name('pravoslavni.osnovni-koncepti');

/*
|--------------------------------------------------------------------------
| Moduli pod /pravoslavni/*
|--------------------------------------------------------------------------
*/
Route::prefix('pravoslavni')->group(function () {

    /* ---------------- Zanimljivosti ---------------- */
    Route::get('/zanimljivosti', [CuriosityController::class, 'index'])
        ->name('curiosities.index');
    Route::get('/zanimljivosti/{slug}', [CuriosityController::class, 'show'])
        ->name('curiosities.show');

    /* ---------------- Datum Vaskrsa ---------------- */
    Route::get('/datum-vaskrsa', [VaskrsController::class, 'index'])
        ->name('vaskrs.index');
    Route::get('/datum-vaskrsa/{slug}', [VaskrsController::class, 'show'])
        ->name('vaskrs.show');

    /* ---------------- Edukacija: interaktivno (PRVO) ---------------- */
    Route::get('/edukacija/timeline', [EdukacijaController::class, 'timeline'])
        ->name('edukacija.timeline');

    Route::get('/edukacija/kviz-istorija', [EdukacijaController::class, 'quizHistory'])
        ->name('edukacija.quiz.history');
    Route::post('/edukacija/kviz-istorija', [EdukacijaController::class, 'quizHistorySubmit'])
        ->name('edukacija.quiz.history.submit');

    Route::get('/edukacija/kviz-pravoslavlje', [EdukacijaController::class, 'quizOrthodox'])
        ->name('edukacija.quiz.orthodox');
    Route::post('/edukacija/kviz-pravoslavlje', [EdukacijaController::class, 'quizOrthodoxSubmit'])
        ->name('edukacija.quiz.orthodox.submit');

    Route::get('/edukacija/ai', [EdukacijaController::class, 'ai'])
        ->name('edukacija.ai');

    /* ---------------- Edukacija: index + slug (NA KRAJU) ---------------- */
    Route::get('/edukacija', [EdukacijaController::class, 'index'])
        ->name('edukacija.index');

    Route::get('/edukacija/{slug}', [EdukacijaController::class, 'show'])
        ->name('edukacija.show');
});

/*
|--------------------------------------------------------------------------
| Redirect starih /edukacija/* URL-ova na NOVI /pravoslavni/edukacija
|--------------------------------------------------------------------------
*/
Route::get('/edukacija', fn () => redirect()->route('edukacija.index'));
Route::get('/edukacija/{any}', fn () => redirect()->route('edukacija.index'))
    ->where('any', '.*');

/*
|--------------------------------------------------------------------------
| GENERIČKI SLUG (uvek NA KRAJU)
|--------------------------------------------------------------------------
*/
Route::get('/pravoslavni/{slug}', [PravoslavniController::class, 'show'])
    ->name('pravoslavni.show');

/*
|--------------------------------------------------------------------------
| Statične stranice (ostalo)
|--------------------------------------------------------------------------
*/
Route::view('/ture', 'pages.tours')->name('tours.index');
Route::view('/ai', 'pages.ai')->name('ai.index');

/*
|--------------------------------------------------------------------------
| Alias rute (kompatibilnost)
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
| Dashboard ne koristiš
|--------------------------------------------------------------------------
*/
Route::redirect('/dashboard', '/');

/*
|--------------------------------------------------------------------------
| Nalog / profil (Breeze) + Omiljeni
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Moj nalog (placeholder)
    Route::view('/nalog', 'pages.account.index')->name('account.index');

    // Omiljeni
    Route::get('/omiljeni', [FavoriteController::class, 'index'])->name('favorites.index');

    // Toggle omiljeni (slug)
    Route::post('/omiljeni/{monastery:slug}/toggle', [FavoriteController::class, 'toggle'])
        ->name('favorites.toggle');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Moderacija manastira
    Route::get('/monasteries', [MonasteryReviewController::class, 'index'])->name('monasteries.index');
    Route::post('/monasteries/{monastery}/approve', [MonasteryReviewController::class, 'approve'])->name('monasteries.approve');
    Route::post('/monasteries/{monastery}/reject', [MonasteryReviewController::class, 'reject'])->name('monasteries.reject');
    Route::post('/monasteries/{monastery}/reset', [MonasteryReviewController::class, 'resetStatus'])->name('monasteries.reset');

    // Import review
    Route::get('/import', [ImportReviewController::class, 'index'])->name('import.index');
    Route::post('/import/approve', [ImportReviewController::class, 'approve'])->name('import.approve');
    Route::post('/import/reject', [ImportReviewController::class, 'reject'])->name('import.reject');
    Route::post('/import/pending', [ImportReviewController::class, 'pending'])->name('import.pending');
    Route::post('/import/delete', [ImportReviewController::class, 'delete'])->name('import.delete');
});


Route::get('/edukacija/ai', [EdukacijaController::class, 'ai'])->name('edukacija.ai');
Route::post('/edukacija/ai/chat', [EdukacijaController::class, 'aiChat'])->name('edukacija.ai.chat');

require __DIR__ . '/auth.php';