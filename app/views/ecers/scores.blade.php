

@extends('layout')

@section('content')

<table class="table table-bordered table-stiped">
    <thead>
    <tr>
        <th>Test</th>
        <th>Page</th>
        <th>Item</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($entryData as $data)
    <tr>
        <td>{{ $data->test }}</td>
        <td>{{ $pageData[$data->test][$data->page] }}</td>
        <td>{{ $data->item }}.{{ $data->item_num }}</td>
        <td>
            @if ($data->value == 0)
                {{ "YES" }}
            @elseif ($data->value == 1)
                {{ "NO" }}
            @else
                {{ "NA" }}
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop