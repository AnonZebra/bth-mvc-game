@extends('layout.base')

@section('title', 'Dice Blackjack')

@section('content')
<h1>@yield('title')</h1>
<h2>Won rounds</h2>
<ul>
    <li>You: {{ $wonRounds["human"] }}</li>
    <li>Computer: {{ $wonRounds["computer"] }}</li>
</ul>
<h2>Ongoing round</h2>
<ul>
    <li>Your score: {{ $scores["human"] }}
        @if ($scores["human"] === 21)
            Congrats!
        @endif
    </li>
    <li>
        Computer score: {{ $scores["computer"] }}
    </li>
</ul>

@if (!$roundHasEnded)
    <form action="" method="POST">
        @csrf
        <input name="roll-dice" id="roll-dice" type="submit" value="Roll"></input>
    </form>

    <form action="" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
        <input name="stay" id="stay" type="submit" value="Stay"></input>
    </form>
@else
    <p>Round ended!</p>
    <p>The winner is: {{ $winner }}</p>
    <form action="" method="POST">
        @csrf
        <input name="new-round" id="new-round" type="submit" value="New round"></input>
    </form>
@endif

<h2>Start new game</h2>
<form action="" method="POST">
    @csrf
    <label for="num-dice">Number of dice</label>
    <input name="num-dice" id="num-dice" type="number"></input>
    <input name="start-new" id="start-new" type="submit" value="New game"></input>
</form>
@endsection

@include('layout.head')
@include('layout.header')
@include('layout.footer')



