<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes();

// Route::middleware('password.confirm')
//     ->get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('/home')->middleware('auth')->group(function () {
    Route::middleware('password.confirm')->get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/show-info', [HomeController::class, 'showInfo'])->name('showInfo');
    Route::put('/update-info', [HomeController::class, 'updateInfo'])->name('updateInfo');


    Route::prefix('/comment')->group(function () {
        Route::get('/show', [HomeController::class, 'showComment'])->name('comment.show');
        Route::post('/search', [HomeController::class, 'searchComment'])->name('comment.search');
        Route::get('/create', [HomeController::class, 'createComment'])->name('comment.create');
        Route::post('/create', [HomeController::class, 'storeComment'])->name('comment.store');
        Route::get('/{comment_id}', [HomeController::class, 'editComment'])->name('commment.edit');
    });
});
