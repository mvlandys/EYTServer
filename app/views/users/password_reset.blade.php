@extends('layout')

@section('content')

<br/>
<div class="row">
    <div class="col-sm-6 col-sm-push-3">
        <div class="well">
            <form id="frmPasswordResetRequest">
                <h2 style="margin: 0">Please enter your email address</h2>
                <br/>
                <input name="email" type="email" class="form-control" placeholder="Email Address..." required autofocus>
                <br/>
                <a class="btn btn-lg btn-primary btn-block" id="btnSubmitPasswordResetRequest">Sign in</a>
            </form>
        </div>
    </div>
</div>

@stop