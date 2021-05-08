@extends('layout.base')

@section('title', 'Dice Blackjack High Scores')

@section('content')
<h1>@yield('title')</h1>
<table>
    <tr>
        <th>Time</th>
        <th>Player name</th>
        <th>Score</th>
    </tr>
@foreach ($gameData as $gd)
    <tr>
        <td>{{ date('Y-m-d H:i',strtotime($gd['time'])) }}</td>
        <td>{{ $gd['playerName'] }}</td>
        <td>{{ $gd['score'] }}</td>
    </tr>
@endforeach
</table>

<p><a href="{{ url('/blackjack') }}">Back to playing Blackjack</a></p>

@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')



