<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $rankType = $request->input('rank', 'popularity');

        $query = Author::query()->withCount('ratings');

        switch ($rankType) {
            case 'popularity':
                $query->withCount(['ratings as popular_votes' => fn($q) => $q->where('rating', '>', 5)
                ])->orderByDesc('popular_votes');
                break;
            case 'average':
                $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
                break;
            case 'trending':
                $query->withAvg([
                    'ratings as recent_avg' => fn($q) =>$q->where('ratings.created_at', '>=', now()->subDays(30)),
                    'ratings as previous_avg' => fn($q) => $q->whereBetween('ratings.created_at', [now()->subDays(60), now()->subDays(30)])
                ], 'rating')->orderByRaw('((COALESCE(recent_avg, 0) - COALESCE(previous_avg, 0)) * ratings_count) DESC');
                break;
        }

        $authors = $query->with([
            'books' => fn($q) => $q->withAvg('ratings', 'rating')
        ])->paginate(20);

        return view('pages.author.index', compact('authors', 'rankType'));
    }
}
