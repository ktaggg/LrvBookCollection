@extends('layout.app')

@section('title', 'Booklist')

@section('content')

    <h3 class="pt-3">Booklist</h3>

    <h5 class="pt-3">Filter Books</h5>
    <form method="GET" action="{{ route('books.index') }}" class="mb-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Category</label>
                <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto;">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category[]"
                                id="category_{{ $category->id }}" value="{{ $category->id }}"
                                {{ in_array($category->id, request()->input('category', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <label for="author" class="form-label">Author</label>
                <select class="form-select" name="author" id="author">
                    <option value="">-- All Authors --</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="yearFrom" class="form-label">Year From</label>
                <input type="number" class="form-control" name="yearFrom" id="yearFrom" value="{{ request('yearFrom') }}">
            </div>
            <div class="col-md-3">
                <label for="yearTo" class="form-label">Year To</label>
                <input type="number" class="form-control" name="yearTo" id="yearTo" value="{{ request('yearTo') }}">
            </div>
            <div class="col-md-3">
                <label for="ratingMin" class="form-label">Rating Min</label>
                <input type="number" class="form-control" name="ratingMin" min="1" max="10"
                    value="{{ request('ratingMin') }}" placeholder="Min: 1">
            </div>
            <div class="col-md-3">
                <label for="ratingMax" class="form-label">Rating Max</label>
                <input type="number" class="form-control" name="ratingMax" min="1" max="10"
                    value="{{ request('ratingMax') }}" placeholder="Max: 10">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" name="status" id="status">
                    <option value="">-- All Statuses --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ ucfirst($status->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label">Location</label>
                <select class="form-select" name="location" id="location">
                    <option value="">-- All Locations --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-end d-flex justify-content-end gap-2">
            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset Filter</a>
            <button class="btn btn-warning" type="submit">Filter</button>
        </div>

    </form>

    <form method="GET" action="{{ route('books.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-10">
                <input class="form-control" type="text" name="search"
                    placeholder="Search by Book title, Author name, ISBN, Publisher..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-warning w-100" type="submit">Search</button>
            </div>
        </div>
    </form>

    <section>
        <table class="w-100 table table-bordered table-responsive">
            <thead class="thead-dark text-center align-middle">
                <tr>
                    <th scope="col">
                        Title
                        @php
                            $current = request('sort_by') === 'title' ? request('sort_order') : null;
                            $next = match ($current) {
                                'asc' => 'desc',
                                'desc' => null,
                                default => 'asc',
                            };
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'title', 'sort_order' => $next]) }}">
                            @if ($current === 'asc')
                                ▲
                            @elseif ($current === 'desc')
                                ▼
                            @else
                                ⇅
                            @endif
                        </a>
                    </th>
                    <th scope="col">Author</th>
                    <th scope="col">Category</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Published Year</th>
                    <th scope="col">Status</th>
                    <th scope="col">Store Location</th>
                    <th scope="col">
                        Avg Rating
                        @php
                            $current = request('sort_by') === 'ratings_avg_rating' ? request('sort_order') : null;
                            $next = match ($current) {
                                'asc' => 'desc',
                                'desc' => null,
                                default => 'asc',
                            };
                        @endphp
                        <a
                            href="{{ request()->fullUrlWithQuery(['sort_by' => 'ratings_avg_rating', 'sort_order' => $next]) }}">
                            @if ($current === 'asc')
                                ▲
                            @elseif ($current === 'desc')
                                ▼
                            @else
                                ⇅
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        Total Ratings
                        @php
                            $current = request('sort_by') === 'votes' ? request('sort_order') : null;
                            $next = match ($current) {
                                'asc' => 'desc',
                                'desc' => null,
                                default => 'asc',
                            };
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'votes', 'sort_order' => $next]) }}">
                            @if ($current === 'asc')
                                ▲
                            @elseif ($current === 'desc')
                                ▼
                            @else
                                ⇅
                            @endif
                        </a>
                    </th>
                    <th>Popularity
                        @php
                            $current = request('sort_by') === 'popularity' ? request('sort_order') : null;
                            $next = match ($current) {
                                'asc' => 'desc',
                                'desc' => null,
                                default => 'asc',
                            };
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'popularity', 'sort_order' => $next]) }}">
                            @if ($current === 'asc')
                                ▲
                            @elseif ($current === 'desc')
                                ▼
                            @else
                                ⇅
                            @endif
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->name }}</td>
                        <td>{{ $book->category->name }}</td>
                        <td class="text-center">{{ $book->isbn }}</td>
                        <td class="text-center">{{ $book->year_published }}</td>
                        <td>{{ ucfirst($book->status->name) }}</td>
                        <td>{{ $book->location->name }}</td>
                        <td class="text-center">{{ number_format($book->ratings->avg('rating'), 1) }}</td>
                        <td class="text-center">{{ $book->ratings->count() }}</td>
                        <td>{{ number_format($book->ratings_avg_rating, 1) }}
                            @if ($book->is_trending)
                                <span style="color:green;font-size:20px;font-weight:bold">↑</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>No Books Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </section>
    <div>
        {{ $books->links() }}
    </div>
@endsection
