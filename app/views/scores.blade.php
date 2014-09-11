

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Card</th>
        <th>Value</th>
        <th>Additional</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($scores as $score)
    <tr>
        <td>{{ $score->card }}</td>
        <td>{{ $score->value }}</td>
        <td>{{ $score->additional }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop