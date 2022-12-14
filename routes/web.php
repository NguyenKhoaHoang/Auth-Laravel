<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RelationshipController;
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

// Auth::routes();

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

    // Route::get('/send-mail', [HomeController::class, 'sendMail'])->name('mail');
    Route::get("email", [HomeController::class, "email"])->name("email");
    Route::post('/send-mail', [HomeController::class, 'smtpEmail'])->name('send-email');

    Route::get('/notification', [HomeController::class, 'notification'])->name('notification');
    Route::get('/post', [HomeController::class, 'post'])->name('post');
    Route::get('/mark-as-read/{id}', [HomeController::class, 'markAsRead'])->name('markAsRead');

    Route::get('/cache', [HomeController::class, 'cache'])->name('cache');
    Route::get('/http-client', [HomeController::class, 'httpClient'])->name('httpClient');

    Route::get('/store-csv', [HomeController::class, 'storeCSV'])->name('storeCSV');

    Route::prefix('/relationship')->group(function () {
        Route::get('/avatar', [RelationshipController::class, 'avatar'])->name('relationship.avatar');
        Route::get('/posts', [RelationshipController::class, 'posts'])->name('relationship.posts');
        Route::get('/categories', [RelationshipController::class, 'categories'])->name('relationship.categories');

        Route::get('/category-attach', [RelationshipController::class, 'categoryAttach'])
            ->name('relationship.categories.attach');

        Route::get('/category-detach', [RelationshipController::class, 'categoryDetach'])
            ->name('relationship.categories.detach');

        Route::get('/category-sync', [RelationshipController::class, 'categorySync'])
            ->name('relationship.categories.sync');

        Route::get('/category-pivot', [RelationshipController::class, 'categoryPivot'])
            ->name('relationship.categories.pivot');

        // Through
        Route::get('/category-post', [RelationshipController::class, 'categoryPost'])
            ->name('relationship.categories.post');


        // Polymorphic
        Route::get('poly-one-one', [RelationshipController::class, 'polyOneOne'])->name('relationship.poly.oneone');
        Route::get('poly-one-many', [RelationshipController::class, 'polyOneMany'])->name('relationship.poly.onemany');
        Route::get('poly-one-create', [RelationshipController::class, 'polyOneCreate'])
            ->name('relationship.poly.onecreate');
        Route::get('poly-many-create', [RelationshipController::class, 'polyManyCreate'])
            ->name('relationship.poly.manycreate');
        Route::get('poly-many-many', [RelationshipController::class, 'polyManyMany'])
            ->name('relationship.poly.manymany');

        // Eager Loading
        Route::get('all-post', [RelationshipController::class, 'allPost'])->name('eager.allPost');
        Route::get('all-comment', [RelationshipController::class, 'imageEagerMorph'])->name('eager.allComments');

        // Querying Relations
        Route::get('condition-relationship', [RelationshipController::class, 'conditionRelationship'])
            ->name('relationship.condition');
    });
});
