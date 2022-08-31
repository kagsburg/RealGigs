<?php

namespace App\Http\Controllers;

use App\Models\listing;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //
// show all listings
    public function index()
    {
        $listings = listing::latest()->filter(request(['tag','search']))->paginate(6);
        return view('listings.index', ['collection' => $listings]);
    }
    // show one listing
    public function show(listing $listing)
    {
        // return the view and pass in the listing
        return view('listings.show', ['listing' => $listing]);
       
    }
    //create form
    public function create(){
        return view('listings.create'); 
    }
    //store function
    public function store (Request $request){
        $formfields = $request->validate([
            'title'=>'required',
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required',
            'company'=>['required', Rule::unique('listings','company')],

        ]);
        if ($request->hasFile('logo')){
            $formfields['logo'] = $request->file('logo')->store('logos','public');
        }
        $formfields['user_id'] = auth()->id();
        listing::create($formfields);
        return redirect('/')->with('success',"Listing Created Successfully");
    }
    //edit form
    public function edit(listing $listing){
        return view('listings.edit', ['listing' => $listing]);
    }
    //update function
    public function update(Request $request, listing $listing){
        //make sure logged in user is owner
        if ($listing->user_id != auth()->id()){
            abort(403,'Unauthorized action');
            return back()->withErrors('You are not the owner of this listing');
        }
        $formfields = $request->validate([
            'title'=>'required',
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email'],
            'tags'=>'required',
            'description'=>'required',
            'company'=>['required' ],
        ]);
        if ($request->hasFile('logo')){
            $formfields['logo'] = $request->file('logo')->store('logos','public');
        }
        $listing->update($formfields);
        return back()->with('success',"Listing Updated Successfully");
    }
    //delete function
    public function delete(listing $listing){
         //make sure logged in user is owner
         if ($listing->user_id != auth()->id()){
            abort(403,'Unauthorized action');
            return back()->withErrors('You are not the owner of this listing');
        }
        $listing->delete();
        return redirect('/')->with('success',"Listing Deleted Successfully");
    }
}
