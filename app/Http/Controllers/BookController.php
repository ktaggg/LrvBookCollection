<?php

namespace App\Http\Controllers;

use App\Filters\BookFilter;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Location;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookFilter $filters, Request $request)
    {
        $request->validate([
            'category' => 'nullable|integer|exists:categories,id',
            'author' => 'nullable|integer|exists:authors,id',
            'year_from' => 'nullable|integer|min:1800|max:'.date('Y'),
            'year_to' => 'nullable|integer|min:1800|max:'.date('Y'),
            'status' => 'nullable|integer|exists:statuses,id',
            'location' => 'nullable|integer|exists:locations,id',
            'rating_min' => 'nullable|integer|min:1|max:10',
            'rating_max' => 'nullable|integer|min:1|max:10'
        ]);

        $categories = Category::all();
        $authors = Author::all();
        $locations = Location::all();
        $statuses = Status::all();
        $min = request('rating_min');
        $max = request('rating_max');

        $query = Book::query()->with([
            'author',
            'category',
            'location',
            'status'
        ]);

        $filters = new BookFilter($query);
        $query = $filters->apply($request->all());
        if ($request->filled('rating_min') && $request->filled('rating_max')) {
            $query->withAvg('ratings', 'rating')
            ->orderBy('ratings_avg_rating', 'asc');
        }

        $books = $query->paginate(200);

        return view('pages.book.index', compact('books', 'categories', 'authors', 'locations', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
