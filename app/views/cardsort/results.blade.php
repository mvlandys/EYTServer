

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Game ID</th>
        <th>Study Name</th>
        <th>Subject ID</th>
        <th>Session ID</th>
        <th>Grade</th>
        <th>DOB</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Played At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($games as $game)
        <tr>
            <td><a href="/vocab/game/{{ $game->id }}">{{ $game->id }}</a></td>
            <td>{{ $game->test_name }}</td>
            <td>{{ $game->subject_id }}</td>
            <td>{{ $game->session_id }}</td>
            <td>{{ $game->grade }}</td>
            <td>
                @if ($game->dob == "")
                .
                @else
                {{ date("d/m/Y",strtotime($game->dob)) }}
                @endif
            </td>
            <td>{{ $game->age }}</td>
            <td>
                @if ($game->sex == 0)
                .
                @else
                    {{ ($game->sex == 1) ? "Male" : "Female" }}
                @endif
            </td>
            <td>{{ date("h:m A, d/m/Y",strtotime($game->played_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop