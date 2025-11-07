<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::get('/books', [BookController::class, 'index'])
        ->name('books.index');

    Route::get('/authors', [AuthorController::class, 'index'])
        ->name('authors.index');

    Route::get('/ratings', [RatingController::class, 'index'])
        ->name('ratings.index');
    Route::post('/ratings/store', [RatingController::class, 'store'])
        ->name('ratings.store');

    Route::get('/books/by-author/{author}', function ($authorId) {
        return \App\Models\Book::where('author_id', $authorId)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();
    })->middleware('auth');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
