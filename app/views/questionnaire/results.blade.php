

@extends('layout')

@section('content')

<div class="well">
    <div class="row">
        <div class="col-sm-3">
            <label>Test Name:</label>
            <select id="test_name">
                <option value="">All Tests</option>
                @foreach ($tests as $test)
                <option {{{ ($test_name == $test["test_name"]) ? "selected" : "" }}} value="{{ $test["test_name"] }}">{{ $test["test_name"] }}</option>
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
            <a class="btn btn-primary" id="btnCardSortFilter">Submit</a>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th><a class="btn btn-warning btn-xs" id="btnQuestionnaireCSV">CSV</a></th>
        <th>Study Name</th>
        <th>Subject ID</th>
        <th>Session ID</th>
        <th>Grade</th>
        <th>DOB</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Played At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($results as $result)
        <tr>
            <td class="text-center"><a class="btn btn-info btn-sm" href="/questionnaire/game/{{ $result->id }}">View Scores</a></td>
            <td>{{{ empty($result->test_name) ? '.' : $result->test_name }}}</td>
            <td>{{{ empty($result->subject_id) ? '.' : $result->subject_id }}}</td>
            <td>{{{ empty($result->session_id) ? '.' : $result->session_id }}}</td>
            <td>{{{ empty($result->grade) ? '.' : $result->grade }}}</td>
            <td>
                @if ($result->dob == "")
                .
                @else
                {{ date("d/m/Y",strtotime($result->dob)) }}
                @endif
            </td>
            <td>{{{ ($result->age == 0) ? '.' : $result->age }}}</td>
            <td>
                @if ($result->sex == 0)
                .
                @else
                    {{ ($result->sex == 1) ? "Male" : "Female" }}
                @endif
            </td>
            <td>{{ date("h:i A, d/m/Y",strtotime($result->played_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop