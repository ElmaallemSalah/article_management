<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware('auth')->group(function () {

 

    //___________________________articles routs _________________________________________________________________________________________
    Route::get('/articles', [ArticleController::class, 'index'])
        ->middleware('permission:read')
        ->name('articles.index');
    Route::get('/article/create', [ArticleController::class, 'create'])
        ->middleware('permission:create')
        ->name('article.create');
    Route::post('/article/store', [ArticleController::class, 'store'])
        ->middleware('permission:create')
        ->name('article.store');
        Route::get('/article/edit/{article}', [ArticleController::class, 'edit'])
        ->middleware('permission:edit')
        ->name('article.edit');
    
    Route::post('/article/update/{article}', [ArticleController::class, 'update'])
        ->middleware('permission:edit')
        ->name('article.update');
    Route::delete('/article/destroy/{article}', [ArticleController::class, 'destroy'])
        ->middleware('permission:delete')
        ->name('article.destroy');

    
});



