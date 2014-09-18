

@extends('layout')

@section('content')

<div class="alert alert-{{ $type }}">{{ $msg }}</div>

@stop