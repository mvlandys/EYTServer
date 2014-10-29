

@extends('layout')

@section('content')

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Level</th>
        <th>Trial</th>
        <th>Value</th>
        <th>Response Time</th>
        <th>Attempted</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->set }}</td>
        <td>{{ $score->rep }}</td>
        <td>{{ ($score->correct == 1) ? "Correct" : "InCorrect" }}</td>
        <td>{{ $score->responseTime }}</td>
        <td>{{ ($score->attempted == 1) ? "Attempted" : "." }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop