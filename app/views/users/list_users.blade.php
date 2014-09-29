@extends('layout')

@section('content')

<a class="btn btn-primary pull-right" href="/admin/newuser">New User</a>
<h2>User Administration</h2>
<br/>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Username</th>
        <th>Administrator</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->username }}</td>
        <td>{{ ($user->admin == 1) ? "YES" : "NO" }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

@stop