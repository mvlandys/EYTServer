

@extends('layout')

@section('content')

@foreach ($games as $game)
    <p>{{ $game }}</p>
@endforeach

@stop