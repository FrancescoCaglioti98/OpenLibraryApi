<?php

use App\Http\Controllers\OpenLibrarySearch;
use Illuminate\Support\Facades\Route;

Route::get('/search', [OpenLibrarySearch::class, 'Search']);
