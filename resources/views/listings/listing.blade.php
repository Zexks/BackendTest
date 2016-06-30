@extends('master')

@section('title')
  'Listings Title'
@stop

@section('content')
<h1>Available Properties</h1>
  <ul>
  @foreach($listings as $listing)
    <li>{{ Html::linkRoute('viewListing', $listing->street . ', ' . $listing->city . ', ' . $listing->state . ' ' . $listing->zip, array($listing->url)) }}</li>
  @endforeach
</ul>
@stop
