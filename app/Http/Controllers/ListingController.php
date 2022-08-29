<?php

namespace App\Http\Controllers;

use App\Models\listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    //
// show all listings
    public function index()
    {
        $listings = listing::latest()->filter(request(['tag','search']))->get();
        return view('listings.index', ['collection' => $listings]);
    }
    // show one listing
    public function show(listing $listing)
    {
        // return the view and pass in the listing
        return view('listings.show', ['listing' => $listing]);
        // return view('',[
        //     'collection'=>[$listing]
        // ]);
    }
}
