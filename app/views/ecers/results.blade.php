

@extends('layout')

@section('content')

<input type="hidden" name="game" value="ecers" />

<div class="well">
    <div class="row">
        <div class="col-sm-3">
            <label>Test Name:</label>
            <select id="test_name">
                <option value="all">All Tests</option>
                @foreach ($tests as $key => $test)
                <option {{{ ($test_name == $test->test_name) ? "selected" : "" }}} value="{{ $key }}">{{ $test->test_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label>Date From:</label>
            <input type="text" id="date_start" placeholder="dd/mm/yyyy" value="{{{ $start or '' }}}" />
        </div>
        <div class="col-sm-3">
            <label>Date To:</label>
            <input type="text" id="date_end" placeholder="dd/mm/yyyy" value="{{{ $end or '' }}}" />
        </div>
        <div class="col-sm-3">
            <a class="btn btn-primary" id="btnEcersFilter">Submit</a>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th><a class="btn btn-warning btn-xs" id="btnEcersCSV">CSV</a></th>
        <th><a href="?order=study">Study Name</a></th>
        <th><a href="?order=centre">Centre</a></th>
        <th><a href="?order=room">Room</a></th>
        <th><a href="?order=observer">Observer</a></th>
        <th><a href="?order=start">Start</a></th>
        <th><a href="?order=end">End</a></th>
        <th class="text-center"><i class="glyphicon glyphicon-trash"></i></th>
        <th class="text-center">
            <a id="btnDeleteGames" class="btn btn-danger btn-block btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php $lastID = 0; ?>
    @foreach ($entries as $entry)
        <tr id="row{{ $entry->id }}">
            <td><a class="btn btn-info btn-sm" href="/ecers/entry/{{ $entry->id }}">View Scores</a></td>
            <td>{{{ empty($entry->study) ? '.' : $entry->study }}}</td>
            <td>{{{ empty($entry->centre) ? '.' : $entry->centre }}}</td>
            <td>{{{ empty($entry->room) ? '.' : $entry->room }}}</td>
            <td>{{{ empty($entry->observer) ? '.' : $entry->observer }}}</td>
            <td>{{ date("h:i A, d/m/Y",strtotime($entry->start)) }}</td>
            <td>{{ date("h:i A, d/m/Y",strtotime($entry->end)) }}</td>
            <td class="text-center"><a class="btn btn-danger btn-xs btnDeleteGame" data-game_type="ecers" data-last_id="{{ $lastID }}" data-entry_id="{{ $entry->id }}" data-game_id="{{ $entry->id }}" data-confirm="0"><i class="glyphicon glyphicon-trash"></i></a></td>
            <td class="text-center"><input class="deleteGames" type="checkbox" data-game_id="{{ $entry->id }}"></td>
            <?php $lastID = $entry->id; ?>
        </tr>
    @endforeach
    </tbody>
</table>

@stop