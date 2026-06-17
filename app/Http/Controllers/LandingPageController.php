<?php

namespace App\Http\Controllers;
use App\Models\Fleet;
use App\Models\UserValidation;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LandingPageController extends Controller
{
    public function getFleet(){
        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

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
        $fleet = Fleet::with(['images', 'user.profile', 'user.fpDetail'])
            ->findOrFail($id);

        $fleets = Fleet::with('images')
            ->where('status', 'active')
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        $bookedRanges = Booking::where('fleet_id', $id)
            ->whereNull('is_cancelled')
            ->orderBy('from_date')
            ->get(['from_date', 'to_date'])
            ->map(fn ($booking) => [
                'from' => \Carbon\Carbon::parse($booking->from_date)->format('Y-m-d'),
                'to' => \Carbon\Carbon::parse($booking->to_date)->format('Y-m-d'),
            ])
            ->values();

        return view('vehicle.show', compact('fleets', 'fleet', 'bookedRanges'));
    }

}
