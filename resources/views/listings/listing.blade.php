@extends('master')

@section('title')
  'Listings Title'
@stop

@section('content')
<h1>Available Properties</h1>
  <ul>
  @foreach($listings as $listing)
    <li>{{ Html::link($listing->url, $listing->mlsnumber) }}</li>
  @endforeach
</ul>
@stop
