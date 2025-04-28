<?php

namespace App\Http\Controllers;

use App\Models\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserValidationApproved;

class VerificationController extends Controller
{
    public function index(){
        $requests = UserValidation::with('user')->get(); 
        return view('verification.index',compact('requests'));
    }

    public function approve(Request $request)
    {
        $requestId = $request->input('id');
        $validationRequest = UserValidation::find($requestId);
        
        if ($validationRequest) {
            $validationRequest->status = 'approved';
            $validationRequest->save();
            $user = $validationRequest->user;
            Mail::to($user->email)->send(new UserValidationApproved($user));

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
