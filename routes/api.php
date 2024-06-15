<?php

use App\Http\Controllers\OpenLibrarySearch;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [OpenLibrarySearch::class, 'Search']);
Route::post('/review', [ReviewController::class, 'postReview']);
