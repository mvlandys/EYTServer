

@extends('layout')

@section('content')

<h3>New User</h3>
<br/>

<form id="frmNewUser">

    <div class="row">
        <div class="col-sm-4">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" placeholder="Username..." />
        </div>
        <div class="col-sm-4">
            <label>Username:</label>
            <input type="password" name="password" class="form-control" placeholder="Password..." />
        </div>
        <div class="col-sm-4">
            <label>Administrator Access:</label>
            <select class="form-control" name="admin">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-sm-4">
            <label>Delete Function:</label>
            <select class="form-control" name="delete">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>CardSort Data:</label>
            <select class="form-control" name="cardsort">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>FishShark Data:</label>
            <select class="form-control" name="fishshark">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-sm-4">
            <label>Mr Ant Data:</label>
            <select class="form-control" name="mrant">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Questionnaire Data:</label>
            <select class="form-control" name="questionnaire">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Vocab Data:</label>
            <select class="form-control" name="vocab">
                <option value="0">DISABLED</option>
                <option value="1">ENABLED</option>
            </select>
        </div>
    </div><br/>

</form>

<a class="btn btn-success btn-lg" id="btnNewUser">Create New User</a>

@stop