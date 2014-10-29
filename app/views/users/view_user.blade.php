@extends('layout')

@section('content')

<h3>View User</h3>
<br/>

<form id="frmUpdateUser">

    <div class="row">
        <div class="col-sm-4">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" placeholder="Username..." disabled required value="{{ $user->username }}" />
        </div>
        <div class="col-sm-4">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Password..." />
        </div>
        <div class="col-sm-4">
            <label>Confirm Password:</label>
            <input type="password" name="password2" class="form-control" placeholder="Password..." />
        </div>
    </div><br/>

    <div class="row">
        <div class="col-sm-4">
            <label>Email Address:</label>
            <input type="email" name="email" class="form-control" placeholder="Email Address..." required value="{{ $user->email }}" />
        </div>
        <div class="col-sm-4">
            <label>Administrator Access:</label>
            <select class="form-control" name="admin">
                <option value="0" {{ ($user->admin == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->admin == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Delete Function:</label>
            <select class="form-control" name="delete">
                <option value="0" {{ ($user->delete == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->delete == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-sm-4">
            <label>CardSort Data:</label>
            <select class="form-control" name="cardsort">
                <option value="0" {{ ($user->cardsort == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->cardsort == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>FishShark Data:</label>
            <select class="form-control" name="fishshark">
                <option value="0" {{ ($user->fishshark == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->fishshark == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Mr Ant Data:</label>
            <select class="form-control" name="mrant">
                <option value="0" {{ ($user->mrant == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->mrant == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-sm-4">
            <label>Questionnaire Data:</label>
            <select class="form-control" name="questionnaire">
                <option value="0" {{ ($user->questionnaire == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->questionnaire == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>Vocab Data:</label>
            <select class="form-control" name="vocab">
                <option value="0" {{ ($user->vocab == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->vocab == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>NotThis Data:</label>
            <select class="form-control" name="notthis">
                <option value="0" {{ ($user->notthis == 0) ? "selected" : "" }}>DISABLED</option>
                <option value="1" {{ ($user->notthis == 1) ? "selected" : "" }}>ENABLED</option>
            </select>
        </div>
    </div><br/>

</form>

<a class="btn btn-success btn-lg" id="btnUpdateUser" data-user_id="{{ $user->id }}">Update User</a>

@stop