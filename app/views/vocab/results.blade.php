

@extends('layout')

@section('content')

<table class="table table-bordered table-striped">
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
        <th>Score</th>
        <th>Played At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($games as $game)
        <tr>
            <td><a class="btn btn-info btn-sm" href="/vocab/game/{{ $game->id }}">View Scores</a></td>
            <td>{{{ empty($game->test_name) ? '.' : $game->test_name }}}</td>
            <td>{{{ empty($game->subject_id) ? '.' : $game->subject_id }}}</td>
            <td>{{{ empty($game->session_id) ? '.' : $game->session_id }}}</td>
            <td>{{{ empty($game->grade) ? '.' : $game->grade }}}</td>
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
            <td>{{ $game->score }}</td>
            <td>{{ date("h:i A, d/m/Y",strtotime($game->played_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop