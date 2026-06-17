<?php

namespace App\Http\Controllers;

use App\Models\FpDetail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class FleetProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']);
    }

    public function index(): View
    {
        $fleetProviders = User::role('FP')
            ->with('fpDetail')
            ->withCount('fleet')
            ->latest()
            ->get();

        return view('fleet-providers.index', compact('fleetProviders'));
    }

    public function create(): View
    {
        return view('fleet-providers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[\w\._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'name.regex' => 'Name should only contain letters and spaces.',
            'email.regex' => 'Please enter a valid email address.',
            'password.min' => 'Password must be at least 8 characters.',
            'address.required' => 'The address field is required.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('FP');

        FpDetail::create([
            'user_id' => $user->id,
            'address' => $validated['address'],
        ]);

        return redirect()->route('fleet-providers.index')
            ->with('success', 'Fleet provider created successfully.');
    }

    public function edit(int $id): View
    {
        $fleetProvider = $this->findFleetProvider($id);

        return view('fleet-providers.edit', compact('fleetProvider'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $fleetProvider = $this->findFleetProvider($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$fleetProvider->id, 'regex:/^[\w\._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'name.regex' => 'Name should only contain letters and spaces.',
            'email.regex' => 'Please enter a valid email address.',
            'password.min' => 'Password must be at least 8 characters.',
            'address.required' => 'The address field is required.',
        ]);

        $fleetProvider->name = $validated['name'];
        $fleetProvider->email = $validated['email'];

        if (! empty($validated['password'])) {
            $fleetProvider->password = Hash::make($validated['password']);
        }

        $fleetProvider->save();

        FpDetail::updateOrCreate(
            ['user_id' => $fleetProvider->id],
            ['address' => $validated['address']]
        );

        return redirect()->route('fleet-providers.index')
            ->with('success', 'Fleet provider updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $fleetProvider = $this->findFleetProvider($id);

        FpDetail::where('user_id', $fleetProvider->id)->delete();
        $fleetProvider->delete();

        return redirect()->route('fleet-providers.index')
            ->with('success', 'Fleet provider deleted successfully.');
    }

    private function findFleetProvider(int $id): User
    {
        $fleetProvider = User::role('FP')->with('fpDetail')->findOrFail($id);

        return $fleetProvider;
    }
}
