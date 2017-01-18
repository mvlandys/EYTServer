

@extends('layout')

@section('content')

    <table class="table table-bordered table-stiped">
        <thead>
        <tr>
            <th>Name</th>

            <th>Level</th>
            <th>Part</th>

            <th>Value</th>
            <th>Response</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($scores as $score)
            <tr>
                <td>Item {{ $score->item }}</td>

                <td>{{ $score->level }}</td>
                <td>{{ $score->part }}</td>

                <td>
                    {{ $score->value }}
                </td>
                <td>
                    {{ $score->response }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop