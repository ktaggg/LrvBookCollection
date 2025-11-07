<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function index()
    {
        $authors = Author::select('id', 'name')->orderBy('name')->get();
        return view('pages.rating.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10'
        ]);

        $userId = Auth::id();
        $book = Book::findOrFail($request->book_id);

        if ($book->author_id !== (int) $request->author_id) {
            return back()->withErrors(['book_id' => 'Selected book does not belong to the chosen author.'])->withInput();
        }

        try {
            Rating::updateOrCreate([
                'user_id' => $userId,
                'book_id' => $book->id,
                'rating' => $request->rating
            ]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withErrors(['rating' => 'You have already rated this book.'])->withInput();
        } catch (\Exception $e) {
            logger()->error('Failed to create or update rating', ['error' => $e->getMessage()]);
            return back()->withErrors(['rating' => 'Failed to submit rating.']);
        }

        return redirect()->route('books.index')->with('success', 'Rating submitted successfully.');
    }
}
