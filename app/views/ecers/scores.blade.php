

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

    <br/><br/>
    <h4>App Notes:</h4>
    <br/>
    <table class="table table-striped table-bordered">
        @foreach($appNotes as $note)
        <tr>
            <td>{{ $note->note }}</td>
        </tr>
        @endforeach
    </table>
    <br/><br/>

    <h3>Page Notes</h3>
    <br/>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Study</th>
            <th>Page</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody>
        @foreach($notes as $note)
        <tr>
            <td>{{$note->test}}</td>
            <td>{{$note->page}}</td>
            <td>{{$note->note}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

@stop