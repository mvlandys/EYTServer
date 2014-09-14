

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Level</th>
        <th>Correct</th>
        <th>Incorrect</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->level }}</td>
        <td>{{ $score->correct }}</td>
        <td>{{ $score->incorrect }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop