<?php

namespace App\Http\Controllers;

use View;
use App\Models\Listing;
use Illuminate\Http\Request;

use App\Http\Requests;

class ListingsController extends Controller
{
  public $restful = true;

  public function index() {
    return View::make('listings.listing')
              ->with('title', 'All Listings')
              ->with('listings', Listing::all());
  }

  public function getByPrice($order) {
    return View::make('listings.listing')
              ->with('title', 'All Listings')
              ->with('listings', Listings::orderby('price', $order)
              ->get());
  }

  public function getByDate($order) {
    return View::make('listings.listing')
              ->with('title', 'All Listings')
              ->with('listing', Listing::orderby('date', $order)
              ->get());
  }

  public function getPhotos($id) {
    return View::make('listinigs.view')
              ->with('title', 'Listing Photos')
              ->with('photos', Photo::find($listing_id));
  }
}
