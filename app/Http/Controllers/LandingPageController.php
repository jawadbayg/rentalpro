<?php

namespace App\Http\Controllers;
use App\Models\Fleet;
use App\Models\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LandingPageController extends Controller
{
    public function getFleet(){
        $userValidated = false;
        if(Auth::check()){
            $auth_id = Auth::user()->id;
            $userValidation = UserValidation::where('user_id',$auth_id)->first();
            if($userValidation){
                $userValidated = true;
            }
        }
        $fleets = Fleet::where('status', 'active')->get();

        return view('landing_page',compact('fleets','userValidated'));
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
