<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\QuoteController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::resources([
        'books' => BookController::class,
        'quotes' => QuoteController::class,
        'reviews' => ReviewController::class,
    ]);
    Route::get('/searchBooks', [BookController::class, 'search']);
    Route::get('/searchQuotes', [QuoteController::class, 'search']);

    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/authors/search', [AuthorController::class, 'search']);
    Route::get('/authors/{id}', [AuthorController::class, 'show']);
    Route::get('my_books', [UserController::class, 'my_books']);
    Route::get('my_quotes', [UserController::class, 'my_liked_quotes']);
    Route::post('profile', [UserController::class, 'profile']);
    Route::put('profile_edit', [UserController::class, 'store']);
    Route::put('/follow/{user}', [UserController::class, 'follow']);

    Route::put('/authors/{author}/follow', [AuthorController::class, 'follow']);

    Route::put('/reviews/{id}/like', [ReviewController::class, 'like']);
    Route::put('/quotes/{id}/like', [QuoteController::class, 'like']);

    Route::post('books/reviews/{book_id}', [BookController::class, 'get_reviews']);

});

