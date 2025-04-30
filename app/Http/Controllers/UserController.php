<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\UserProfile;
use App\Models\UserValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\returnArgument;
    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::latest()->paginate(5);
  
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function profilePage($id)
    {
        $user = User::findOrFail($id); 

        return view('partials.profile_settings', compact('user'));
    }
    public function uploadProfilePicture(Request $request, $id)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $user = User::findOrFail($id); 
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    
        $profile = $user->profile;
    
        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
        } else {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
        }
        $profile->profile_picture = $path;
        $profile->save();
    
        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    public function createUserVerification(){
        return view('partials.user_validation');
    }
    public function userValidationStore(Request $request)
{
    $request->validate([
        'identity_number' => [
            'required',
            'numeric',
            'digits_between:6,20', // optional: limit length
            'regex:/^[0-9]+$/'
        ],
        'license_number' => [
            'required',
            'regex:/^[A-Z0-9\-]{5,20}$/i' // e.g. "DL-12345", case-insensitive
        ],
        'license_provider' => [
            'required',
            'regex:/^[A-Za-z\s]+$/', // only letters and spaces
            'max:255'
        ],
        'age' => [
            'required',
            'numeric',
            'min:18',
            'max:100',
            'regex:/^[0-9]{2,3}$/'
        ],
        'address' => [
            'required',
            'regex:/^[A-Za-z0-9\s,\-\.]{5,500}$/', // basic characters
            'max:500'
        ],
    ], [
        // Custom error messages (optional)
        'identity_number.regex' => 'Identity number must contain only digits.',
        'license_number.regex' => 'License number must be alphanumeric and 5–20 characters.',
        'license_provider.regex' => 'License provider must contain only letters.',
        'age.regex' => 'Age must be a valid number.',
        'address.regex' => 'Address format is invalid.',
    ]);

    UserValidation::create([
        'user_id' => Auth::id(),
        'identity_number' => $request->identity_number,
        'license_number' => $request->license_number,
        'license_provider' => $request->license_provider,
        'age' => $request->age,
        'address' => $request->address,
    ]);

    return redirect()->back()->with('success', 'Validation information submitted successfully.');
}

}
