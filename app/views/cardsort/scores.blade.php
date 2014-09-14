

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Level</th>
        <th>Card</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->level }}</td>
        <td>{{ $score->card }}</td>
        <td>{{ $score->value }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop