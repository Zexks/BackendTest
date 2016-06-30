@extends('master')

@section('content')
  <h1>{{ $listing->street }}</h1>

  <p>{{ $listing->price }}</p>
@stop
