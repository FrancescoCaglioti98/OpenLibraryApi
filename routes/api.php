<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\OpenLibrarySearch;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [OpenLibrarySearch::class, 'Search']);
Route::post('/review', [ReviewController::class, 'postReview']);
Route::get('/review/{reviewID}', [ReviewController::class, 'getReview']);
Route::put('review/{reviewID}', [ReviewController::class, 'putReview']);
Route::delete('/review/{reviewID}', [ReviewController::class, 'deleteReview']);

// Optional endpoint
Route::get('/work/{workID}', [WorkController::class, 'getWork']);
Route::get('/author/{authorID}', [AuthorController::class, 'getAuthor']);
