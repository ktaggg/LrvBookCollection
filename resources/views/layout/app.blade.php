<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>@yield('title')</title>
</head>

<body class="min-vh-100">

    <style>
        a {
            color: black;
            text-decoration: none;
        }

        .head-nav:hover {
            text-decoration: underline;
            text-decoration-color: orange;
        }
    </style>

    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <h1 class="px-2">JDBooks</h1>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{ route('home') }}" class="head-nav nav-link px-2 text-white">Home</a></li>
                    <li><a href="{{ route('books.index') }}" class="head-nav nav-link px-2 text-white">Booklist</a></li>
                    <li><a href="{{ route('authors.index') }}" class="head-nav nav-link px-2 text-white">Top Authors</a></li>
                    <li><a href="{{ route('ratings.index') }}" class="head-nav nav-link px-2 text-white">Rate Books</a></li>
                </ul>

                <div class="text-end">
                    @guest
                    <a href="{{ route('login') }}" type="button" class="btn btn-outline-light me-2">Login</a>
                    <a href="{{ route('register') }}" type="button" class="btn btn-warning">Sign-up</a>
                    @else
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">Logout</button>
                    </form>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        @yield('content')
    </div>

    <footer class="text-center pt-2">
        <p>Copyright &copy; 2025 JDBooks by Ktag</p>
    </footer>

</body>

</html>
