@extends('layout')

@section('content')

<a class="btn btn-primary pull-right" id="btnNewAppUser">New User</a>
<h2>App Access</h2>
<br/>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Username</th>
        <th style="width: 115px"></th>
        <th style="width: 40px;"></th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->username }}</td>
        <td><a class="btn btn-sm btn-warning btnSetAppUserPassword" data-user_id="{{ $user->id }}">Set Password</a></td>
        <td><a class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-trash"></i></a></td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop