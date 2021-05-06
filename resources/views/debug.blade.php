@extends('layout.base')

@section('title', 'Debug info')

@section('content')
<h1>@yield('title')</h1>

<p>{{ var_dump($_SERVER) }}</p>
@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')