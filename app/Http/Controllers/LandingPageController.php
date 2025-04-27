<?php

namespace App\Http\Controllers;
use App\Models\Fleet;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function getFleet(){
        $fleets = Fleet::where('status', 'active')->get();
        return view('landing_page',compact('fleets'));
    }
    public function show($id)
    {
        $fleets = Fleet::where('status', 'active')
        ->where('id', '!=', $id)
        ->take(3)->get(); // for showing more cards below show page
        $fleet = Fleet::findOrFail($id);
        return view('vehicle.show', compact('fleets','fleet'));
    }

}
