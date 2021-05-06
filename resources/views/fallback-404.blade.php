@extends('layout.base')

@section('title', '404')

@section('content')
<h1>@yield('title')</h1>

<p>The page could not be found. Please use the links above to navigate the site.</p>
@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')