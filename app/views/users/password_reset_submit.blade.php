@extends('layout')

@section('content')
<br/><br/>
<form id="frmPasswordReset" class="well">
    <h3 style="margin-top: 0">Reset Password</h3>
    <br/>
    <div class="row">
        <div class="col-sm-3">
            <label>Reset Code</label>
            <input type="text" class="form-control" required name="code" value="{{ $code }}" />
        </div>
        <div class="col-sm-3">
            <label>New Password</label>
            <input type="password" class="form-control" required name="password" placeholder="New Password" />
        </div>
        <div class="col-sm-3">
            <label>Confirm New Password</label>
            <input type="password" class="form-control" required name="password2" placeholder="Confirm New Password" />
        </div>
        <div class="col-sm-3">
            <br/>
            <a class="btn btn-primary btn-block" id="btnSubmitPasswordReset">Submit</a>
        </div>
    </div>
</form>

@stop