@extends('layout')

@section('content')

<br/>
<div class="row">
    <div class="col-sm-6 col-sm-push-3">
        <div class="well">
            <form id="frmLogin">
                <h2 style="margin: 0">Please sign in</h2>
                <br/>
                <input name="username" type="text" class="form-control" placeholder="Username..." required autofocus>
                <br/>
                <input name="password" type="password" class="form-control" placeholder="Password..." required>
                <br/>
                <a class="btn btn-lg btn-primary btn-block" id="btnLogin">Sign in</a>
                <br/>
                <a href="/passwordreset/request">Forgot Password</a>
            </form>
        </div>
    </div>
</div>

@stop