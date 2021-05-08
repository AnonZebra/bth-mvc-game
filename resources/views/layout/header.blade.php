@section('header')
<nav>
        <a href="{{ url('/') }}">Home</a> |
        <a href="{{ url('session') }}">Session</a> |
        <a href="{{ url('debug') }}">Debug</a> |
        <a href="{{ url('incorrect-link') }}">Show 404 example</a> |
        <a href="{{ url('blackjack') }}">Blackjack</a> |
        <a href="{{ url('books') }}">Books</a> |
        <a href="#">Yahtzee</a>
</nav>
@endsection