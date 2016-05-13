@extends('layout')

@section('content')

<div class="well">
    <h1>EYT Games Database</h1>
    <hr/>
    <div class="row">
        <div class="col-sm-6">
            <p>intro text about EYT Server</p>
        </div>
        <form class="col-sm-6" id="frmGameData">
            <h4>Data File Upload</h4>
            <br/>
            <div class="row">
                <div class="col-sm-6">
                    <label>Select the file:</label>
                    <input type="file" name="game_data" /><br/>
                </div>
                <div class="col-sm-6">
                    <label>Game Type:</label><br/>
                    <select name="game_type" class="form-control">
                        <option value="0">Please Select</option>
                        <option value="cardsort">Card Sort</option>
                        <option value="ecers">Ecers</option>
                        <option value="fishshark">Fish Shark</option>
                        <option value="mrant">Mr Ant</option>
                        <option value="notthis">Not This</option>
                        <option value="vocab">Vocab</option>
                    </select>
                </div>
            </div>
            <br/>
            <a class="btn btn-block btn-primary" id="btnUploadGameData">Upload</a>
        </form>
    </div>

    <!--
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
    -->
</div>

@stop