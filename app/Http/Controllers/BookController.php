<?php

namespace App\Http\Controllers;

use App\Filters\BookFilter;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Location;
use App\Models\Status;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookFilter $filters, Request $request)
    {
        $request->validate([
            'category' => 'nullable|array',
            'category.*' => 'integer|exists:categories,id',
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
            'categories',
            'location',
            'status',
        ])->withCount('ratings');

        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order');

        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sortOrder ?? 'asc');
                break;
            case 'votes':
                $query->orderBy('ratings_count', $sortOrder ?? 'desc');
                break;
            case 'ratings_avg_rating':
                $query->withAvg('ratings', 'rating')
                ->orderBy('ratings_avg_rating', $sortOrder ?? 'desc');
                break;
            case 'popularity':
                $query->withCount(['ratings as recent_votes' => function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                }])->orderBy('recent_votes', $sortOrder ?? 'desc');
                break;
            default:
        }

        $query->withAvg('ratings', 'rating');
        $query->withAvg(['ratings as rating_7_days_ago' => function($q) {
            $q->where('created_at', '<', now()->subDays(7));
        }], 'rating');

        $query->selectRaw('
            CASE
                WHEN (SELECT AVG(rating) FROM ratings WHERE book_id = books.id AND created_at >= ?) >
                (SELECT AVG(rating) FROM ratings WHERE book_id = books.id AND created_at < ?)
                THEN 1 ELSE 0
            END as is_trending
        ', [now()->subDays(7), now()->subDays(7)]);

        $filters = new BookFilter($query);
        $query = $filters->apply($request->all());
        if ($request->filled('rating_min') && $request->filled('rating_max')) {
            $query->withAvg('ratings', 'rating')
            ->orderBy('ratings_avg_rating', 'asc');
        }

        $books = $query->paginate(50);

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
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer|exists:authors,id',
            'publisher' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1800|max:'.date('Y'),
            'isbn' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,id',
            'status_id' => 'required|integer|exists:statuses,id',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $book = Book::create($request->except('categories'));
        $book->categories()->attach($request->categories);

        return redirect()->route('books.index');
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
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|integer|exists:authors,id',
            'publisher' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1800|max:'.date('Y'),
            'isbn' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,id',
            'status_id' => 'required|integer|exists:statuses,id',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->except('categories'));
        $book->categories()->sync($request->categories);

        return redirect()->route('books.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
