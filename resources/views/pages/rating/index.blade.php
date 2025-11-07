@extends('layout.app')

@section('title', 'Booklist')

@section('content')

    <div class="mb-5 d-flex justify-content-center align-items-center" style="min-height: 70vh">
        <div class="card mt-3 p-2 w-100">
            <div class="card-header pt-1">
                <h3>Rate a Book</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('ratings.store') }}" class="form-label">
                    @csrf
                    <div class="row p-3">
                        <div class="col">
                            <label for="author_id">
                                <h6>Author</h6>
                            </label>
                            <select class="form-select" name="author_id" id="author-select">
                                <option value="">-- Select Author --</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label for="book_id">
                                <h6>Book Title</h6>
                            </label>
                            <select class="form-select" name="book_id" id="book-select">
                                <option value="">-- Select Book --</option>
                            </select>
                        </div>

                        <div class="col">
                            <label for="">
                                <h6>Rating:</h6>
                            </label>
                            <select class="form-select" name="rating" id="">
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-end">Submit Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const authorSelect = document.getElementById('author-select');
            const bookSelect = document.getElementById('book-select');
            const oldBookId = '{{ old('book_id') }}';

            function fetchBooks(authorId, selectedBookId = null) {
                bookSelect.innerHTML = '<option value="">Loading...</option>';
                bookSelect.disabled = true;

                if (authorId) {
                    fetch(`/books/by-author/${authorId}`)
                        .then(response => response.json())
                        .then(books => {
                            bookSelect.innerHTML = '<option value="">-- Select Book --</option>';
                            books.forEach(book => {
                                const option = document.createElement('option');
                                option.value = book.id;
                                option.textContent = book.title;
                                if (book.id == selectedBookId) {
                                    option.selected = true;
                                }
                                bookSelect.appendChild(option);
                            });
                            bookSelect.disabled = false;
                        });
                } else {
                    bookSelect.innerHTML = '<option value="">-- Select Book --</option>';
                    bookSelect.disabled = true;
                }
            }

            authorSelect.addEventListener('change', function() {
                fetchBooks(this.value);
            });

            if (authorSelect.value) {
                fetchBooks(authorSelect.value, oldBookId);
            }
        });
    </script>
@endsection
