<!doctype html>
<html>
<head>
@yield('head')
</head>

<body>
    <header>
        @yield('header')
    </header>
    <main @if (isset($mainClass)) class='{{ $mainClass }}' @endif>
        @if (session()->has('errmsg'))
            <p class="error-msg">Error: {{ session('errmsg') }}</p>
            {{ session(['errmsg' => null]) }}
        @endif

        @yield('content')

    </main>

    <footer>
        @yield('footer')
    </footer>
</body>
</html>
