@extends('layout.app')

@section('title', 'Booklist')

@section('content')

    <form method="GET" action="{{ route('books.index') }}">
        <div class="row">
            <div class="col-sm-8">
                <label for="category">Category</label>
                <select class="w-100" name="category[]" id="category" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, request()->input('category', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-4">
                <label for="author">Author</label>
                <select name="author" id="author">
                    <option value="">-- All Authors --</option>
                    @foreach ($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col d-flex">
                <div>
                    <label for="yearFrom">Year From</label>
                    <input type="number" name="yearFrom" id="yearFrom" value="{{ request('yearFrom') }}">
                </div>
                <div>
                    <label for="yearTo">Year To</label>
                    <input type="number" name="yearTo" id="yearTo" value="{{ request('yearTo') }}">
                </div>
            </div>

            <div>
                <label for="status">Status</label>
                <select name="status">
                    <option value="">-- All Statuses --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ ucfirst($status->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="location">Location</label>
                <select name="location" id="location">
                    <option value="">-- All Location --</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="ratingMin">Rating Min</label>
                <input type="number" name="ratingMin" min="1" max="10" value="{{ request('ratingMin') }}">
            </div>
            <div>
                <label for="ratingMax">Rating Max</label>
                <input type="number" name="ratingMax" min="1" max="10" value="{{ request('ratingMax') }}">
            </div>

            <div>
                <button type="submit">Filter</button>
            </div>
        </div>
    </form>

    <section>
        <table class="w-100 table table-bordered table-responsive">
            <thead class="thead-dark text-center align-middle">
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Category</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Published Year</th>
                    <th scope="col">Status</th>
                    <th scope="col">Store Location</th>
                    <th scope="col">Avg Rating</th>
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
