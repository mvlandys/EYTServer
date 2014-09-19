

@extends('layout')

@section('content')

<br/><br/>

<div class="row">
    <div class="col-sm-6 col-sm-push-3">
        <div class="alert alert-{{ $type }}">{{ $msg }}</div>
    </div>
</div>

@stop