

@extends('layout')

@section('content')

    <h3>Subscale Values</h3>
    <table class="table table-bordered table-stiped">
        @foreach($subscales as $key => $val)
        <tr>
            <td>{{$key}}</td>
            <td>{{ ($val == ".") ? "." : number_format($val, 2) }}</td>
        </tr>
        @endforeach
    </table>

    @foreach ($scores as $key => $entries)

    <table class="table table-bordered table-stiped">
        <thead>
        <tr>
            <th colspan="3">{{$key}}</th>
            <th>Page Score: {{ $entries["score"] }}</th>
        </tr>
        <tr>
            <th style="width: 130px">Test</th>
            <th>Page</th>
            <th style="width: 130px">Item</th>
            <th style="width: 130px">Value</th>
        </tr>
        </thead>
        @foreach ($entries["data"] as $data )
        <tbody>
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
    @endforeach

    <br/><br/>
    <h4>Questions</h4>
    <br/>
    <table class="table table-striped table-bordered">
        @foreach($questions as $question)
        <tr>
            <td>{{$question->study}} {{$question->item}}</td>
            <td>{{$question->answer}}</td>
        </tr>
        @endforeach
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