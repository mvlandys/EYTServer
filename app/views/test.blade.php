

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Game ID</th>
        <th>Test Name</th>
        <th>Subject ID</th>
        <th>Session ID</th>
        <th>Grade</th>
        <th>DOB</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($games as $game)
        <tr>
            <td>{{ $game->id }}</td>
            <td>{{ $game->test_name }}</td>
            <td>{{ $game->subject_id }}</td>
            <td>{{ $game->session_id }}</td>
            <td>{{ $game->grade }}</td>
            <td>{{ $game->dob }}</td>
            <td>{{ $game->age }}</td>
            <td>{{ $game->sex }}</td>
            <td>{{ $game->score }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<hr/>

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Game ID</th>
        <th>Card</th>
        <th>Value</th>
        <th>Additional</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->game_id }}</td>
        <td>{{ $score->card }}</td>
        <td>{{ $score->value }}</td>
        <td>{{ $score->additional }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop