<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api/v1'], function () {
    Route::post('trending_books', [BookController::class, 'trending_books']);
    Route::post('suggestions', [BookController::class, 'suggestions']);
    Route::post('all-reviews/{book}', [ReviewController::class, 'getReviews']);
    Route::get('all-reviews', [ReviewController::class, 'getAllReviews']);
    require __DIR__.'/auth.php';
    require __DIR__.'/authRoutes.php';
});


