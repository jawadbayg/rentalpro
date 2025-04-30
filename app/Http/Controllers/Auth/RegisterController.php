<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{

    use RegistersUsers;

        
    protected function redirectTo()
    {
        $user = Auth::user(); // Optional alias for clarity

        if ($user->hasRole('FP')) {
            return '/home';
        }

        return '/';
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],  // Name should contain only letters and spaces
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[\w\._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],  // Email format
            'password' => ['required', 'string', 'min:8', 'confirmed'],  // Password should be at least 8 characters
            'role' => ['required', 'in:FP,User'],  // Validate role
        ], [
            'name.regex' => 'Name should only contain letters and spaces.',
            'email.regex' => 'Please enter a valid email address.',
            'password.min' => 'Password must be at least 8 characters.',
            'role.required' => 'Please select a role.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign role based on the selected option
        if ($data['role'] == 'FP') {
            $user->assignRole('FP');  // Assuming you have 'FP' role created
        } else {
            $user->assignRole('User');  // Default to 'User' role
        }

        return $user;
    }
}
