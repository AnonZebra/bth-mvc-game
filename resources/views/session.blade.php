@extends('layout.base')

@section('title', 'Session details')

@section('content')
<h1>@yield('title')</h1>

<p>Here are some details on the session. Reload this page to see the counter increment itself.</p>
<p>You may <a href="/session/destroy">destroy the session</a> if you like.</p>

{{ var_dump(Session::all()) }}
@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')


<?php 
if (array_key_exists('counter', Session::all())) {
    session(['counter' => session('counter') + 1]);
} else {
    session(['counter' => 1]);
}
?>

