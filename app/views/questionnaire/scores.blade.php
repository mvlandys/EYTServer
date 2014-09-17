

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Question</th>
        <th>Answer</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->question }}</td>
        <td>{{ ($score->answer == -1) ? '.' : $score->answer }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop