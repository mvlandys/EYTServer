

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
        @if ($score->question < 35)
            <tr>
                <td>{{ $score->question }}</td>
                <td>{{ $score->answer }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@stop