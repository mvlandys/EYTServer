

@extends('layout')

@section('content')

    <table class="table table-bordered table-stiped">
        <thead>
        <tr>
            <th>Item</th>
            <th>Value</th>
            <th>Response</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($scores as $score)
            <tr>
                <td>{{ $score['item'] }}</td>
                <td>
                    {{ $score['value'] }}
                </td>
                <td>
                    {{ $score['response'] }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop