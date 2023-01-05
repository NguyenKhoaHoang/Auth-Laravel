<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/test-upload", [HomeController::class, 'uploadAPI'])->name('uploadAPI');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/aaaa', function () {
    return response()->json([
        'ngon2' => true
    ]);
});

Route::post('/login', [HomeController::class, 'login']);

Route::middleware(['auth:user'])->group(function () {
    Route::get('/user', function () {
        return response()->json([
            'ngon' => true
        ]);
    });
});
