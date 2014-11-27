@extends('layout')

@section('content')

<div class="well">
    <h1>EYT Games Database</h1>
    <br/>
    <div class="row">
        <div class="col-sm-4">
            <h3>Download All Game Data</h3>
            <br/>
            <b style="color: red">*</b> Select A Study:<br/>
            <select name="test_name" class="form-control">
                <option value="all">All Studies</option>
                @foreach($tests as $key => $val)
                <option value="{{ $key }}">{{ $val->test_name }}</option>
                @endforeach
            </select><br/>
            Start Date: <input class="form-control" type="text" name="start" placeholder="dd/mm/yyyy" /><br/>
            End Date: <input class="form-control" type="text" name="end" placeholder="dd/mm/yyyy" /><br/>
            <a class="btn btn-primary btn-block" id="btnAllGameData">Submit</a>
        </div>
    </div>
</div>

@stop