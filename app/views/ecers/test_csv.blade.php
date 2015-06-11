@extends('layout')

@section('content')

</div>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
    @foreach ($header as $col)
        <td>{{ $col }}</td>
    @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($rows as $row)
    <tr>
        @foreach ($row as $col)
        <td>{{ $col }}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

@stop