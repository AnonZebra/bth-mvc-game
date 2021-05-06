@extends('layout.base')

@section('title', 'Index page')

@section('content')
<h1>@yield('title')</h1>

<p>Hello, this is the index page, rendered as a layout.</p>
@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')