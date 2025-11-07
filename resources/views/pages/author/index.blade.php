@extends('layout.app')

@section('title', 'Top 20 Authors')

@section('content')

<h3 class="pt-3">Top 20 Authors</h3>

<h5 class="pt-3">Filter By</h5>

<ul class="nav nav-tabs my-3">
    <li class="nav-item">
        <a class="nav-link {{ $rankType === 'popularity' ? 'active' : '' }}"
           href="{{ route('authors.index', ['rank' => 'popularity']) }}">By Popularity</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $rankType === 'average' ? 'active' : '' }}"
           href="{{ route('authors.index', ['rank' => 'average']) }}">By Average Rating</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $rankType === 'trending' ? 'active' : '' }}"
           href="{{ route('authors.index', ['rank' => 'trending']) }}">By Trending</a>
    </li>
</ul>

<div class="tab-content" id="authorTabContent">
    <div class="tab-pane fade {{ $rankType === 'popularity' ? 'show active' : '' }}" id="popularity" role="tabpanel">
        @if ($rankType === 'popularity')
            <table class="table table-bordered table-responsive w-100">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Author</th>
                        <th>Total Ratings</th>
                        <th>Best Book</th>
                        <th>Worst Book</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($authors as $author)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->ratings_count }}</td>
                        <td>{{ $author->books->sortByDesc('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                        <td>{{ $author->books->sortBy('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="tab-pane fade {{ $rankType === 'average' ? 'show active' : '' }}" id="average" role="tabpanel">
        @if ($rankType === 'average')
            <table class="table table-bordered table-responsive w-100">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Author</th>
                        <th>Total Ratings</th>
                        <th>Best Book</th>
                        <th>Worst Book</th>
                        <th>Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($authors as $author)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->ratings_count }}</td>
                        <td>{{ $author->books->sortByDesc('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                        <td>{{ $author->books->sortBy('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                        <td>{{ number_format($author->ratings_avg_rating, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="tab-pane fade {{ $rankType === 'trending' ? 'show active' : '' }}" id="trending" role="tabpanel">
        @if ($rankType === 'trending')
            <table class="table table-bordered table-responsive w-100">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Author</th>
                        <th>Total Ratings</th>
                        <th>Best Book</th>
                        <th>Worst Book</th>
                        <th>Trending ↑</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($authors as $author)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->ratings_count }}</td>
                        <td>{{ $author->books->sortByDesc('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                        <td>{{ $author->books->sortBy('ratings_avg_rating')->first()?->title ?? '-' }}</td>
                        <td>
                            @php
                                $recent = $author->recent_avg ?? 0;
                                $previous = $author->previous_avg ?? 0;
                                $score = ($recent - $previous) * $author->ratings_count;
                            @endphp
                            {{ number_format($score, 1) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
