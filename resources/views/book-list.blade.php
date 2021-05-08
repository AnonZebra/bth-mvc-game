@extends('layout.base')

@section('title', 'Books')

@section('content')
<h1>@yield('title')</h1>
<ul class="col-multi">
    <!-- <tr>
        <th>Book title</th>
        <th>ISBN</th>
        <th>Author</th>
    </tr> -->
@foreach ($bookData as $book)
    <li class="book-card">
        <div class="info-container">
            <h2>{{ $book['title'] }}</h2>
            <p>Author: {{ $book['author'] }}</p>
            <p>ISBN: {{ $book['isbn'] }}</p>
    </div>
        <img src="{{ url('/img/' . $book['cover_img_filename']) }}">
    </li>
@endforeach
</ul>

@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')



