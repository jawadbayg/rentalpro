<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Default fallback

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        // Custom validation
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'regex:/^[\w\._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid address.',
            'email.regex' => 'Please enter a valid email format.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email', 'remember'));
        }

        // Attempt login
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // On failed login
        return redirect()->back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput($request->only('email', 'remember'));
    }
    protected function authenticated($request, $user)
    {
        if ($user->hasRole('User')) {
            return redirect('/');
        } elseif ($user->hasRole('Admin')) {
            return redirect('/home'); 
        }

        return redirect('/home');
    }
}
