<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\FleetImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FleetController extends Controller
{
    public function index()
    {
        
        $isAdmin = Auth::user()->hasRole('Admin');
        if ($isAdmin) {
            $fleets = Fleet::with('user')->latest()->get();
        } else {
            $fleets = Fleet::where('user_id', Auth::user()->id)->latest()->get();
        }
        return view('fleet.index', compact('fleets', 'isAdmin'));
    }

    public function show($id)
    {
        return view('fleet.show', compact('id'));
    }

    public function create()
    {
        $isAdmin = Auth::user()->hasRole('Admin');
        $fleetProviders = $isAdmin
            ? User::role('FP')->orderBy('name')->get(['id', 'name', 'email'])
            : collect();

        return view('fleet.create', compact('isAdmin', 'fleetProviders'));
    }

    public function store(Request $request)
    {
        $isAdmin = Auth::user()->hasRole('Admin');

        if (! $isAdmin) {
            $request->merge(['user_id' => Auth::id()]);
        }

        $request->merge([
            'license_plate' => $this->normalizeLicensePlate($request->input('license_plate', '')),
        ]);

        try {
            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'vehicle_name' => ['required', 'regex:/^[a-zA-Z0-9\s\-]+$/', 'max:255'],
                'vehicle_owner_name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'registration_date' => ['required', 'date'],
                'vehicle_type' => ['required', Rule::in(['Sedan', 'SUV', 'Jeep', 'Other'])],
                'license_plate' => ['required', 'regex:/^[A-Z0-9]{4,}$/', 'max:255', 'unique:fleet,license_plate'],
                'status' => ['required', 'in:active,inactive,under_maintenance'],
                'mileage' => ['nullable', 'integer'],
                'fuel_type' => ['nullable', Rule::in(['Petrol', 'Diesel', 'EV'])],
                'images' => ['nullable', 'array'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'charges_per_day' => ['required']
            ], [
                'vehicle_name.required' => 'Vehicle name is required.',
                'vehicle_name.regex' => 'Vehicle name must contain only letters, numbers, spaces, or dashes.',

                'vehicle_owner_name.required' => 'Owner name is required.',
                'vehicle_owner_name.regex' => 'Owner name must contain only letters and spaces.',

                'license_plate.required' => 'License plate is required.',
                'license_plate.regex' => 'License plate must be at least 4 characters with uppercase letters and numbers only.',
                'license_plate.unique' => 'This license plate is already in use.',

                'vehicle_type.required' => 'Vehicle type is required.',
                'vehicle_type.in' => 'Please select a valid vehicle type.',

                'charges_per_day.required' => 'Charges per day is required',

                'user_id.required' => 'Please select a fleet provider.',
                'user_id.exists' => 'The selected fleet provider is invalid.',

                'status.required' => 'Vehicle status is required.',
                'status.in' => 'Invalid status selected.',

                'images.*.image' => 'Each file must be an image.',
                'images.*.mimes' => 'Images must be jpeg, png, jpg, gif, or svg.',
                'images.*.max' => 'Each image must be less than 2MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $fleetProvider = User::findOrFail($request->user_id);

        if (! $fleetProvider->hasRole('FP')) {
            return back()
                ->withErrors(['user_id' => 'Please select a valid fleet provider.'])
                ->withInput();
        }

        $fleet = new Fleet();
        $fleet->user_id = $request->user_id;
        $fleet->vehicle_name = $request->vehicle_name;
        $fleet->vehicle_owner_name = $request->vehicle_owner_name;
        $fleet->registration_date = $request->registration_date;
        $fleet->vehicle_type = $request->vehicle_type;
        $fleet->license_plate = $request->license_plate;
        $fleet->status = $request->status;
        $fleet->mileage = $request->mileage;
        $fleet->fuel_type = $request->fuel_type;
        $fleet->price_per_day = $request->charges_per_day;
        $fleet->rental_status = 'Available';
        $fleet->no_of_seats = $request->no_of_seats;
        $fleet->no_of_doors = $request->no_of_doors;
        $fleet->no_of_bags = $request->no_of_bags;
        $fleet->color = $request->color;
        $fleet->save();

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('fleet_images', 'public');
                FleetImage::create([
                    'fleet_id' => $fleet->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return redirect()->route('fleet.index')->with('success', 'Vehicle added successfully!');
    }
    public function edit($id)
    {
        $fleet = Fleet::findOrFail($id);

        return view('fleet.edit', compact('fleet'));
    }


public function update(Request $request, $id)
{
    $request->merge([
        'license_plate' => $this->normalizeLicensePlate($request->input('license_plate', '')),
    ]);

    try {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vehicle_name' => 'required|string|max:255',
            'vehicle_owner_name' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'vehicle_type' => ['required', Rule::in(['Sedan', 'SUV', 'Jeep', 'Other'])],
            'license_plate' => [
                'required',
                'regex:/^[A-Z0-9]{4,}$/',
                'max:255',
                Rule::unique('fleet')->ignore($id),
            ],
            'status' => 'required|in:active,inactive,under_maintenance',
            'mileage' => 'nullable|integer',
            'fuel_type' => ['nullable', Rule::in(['Petrol', 'Diesel', 'EV'])],
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'license_plate.regex' => 'License plate must be at least 4 characters with uppercase letters and numbers only.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    }

    $fleet = Fleet::findOrFail($id);
    $fleet->user_id = $request->user_id;
    $fleet->vehicle_name = $request->vehicle_name;
    $fleet->vehicle_owner_name = $request->vehicle_owner_name;
    $fleet->registration_date = $request->registration_date;
    $fleet->vehicle_type = $request->vehicle_type;
    $fleet->license_plate = $request->license_plate;
    $fleet->status = $request->status;
    $fleet->mileage = $request->mileage;
    $fleet->fuel_type = $request->fuel_type;

    $fleet->save();

    if ($request->has('images')) {
        FleetImage::where('fleet_id', $fleet->id)->delete();
        
        foreach ($request->file('images') as $image) {
            $imagePath = $image->store('fleet_images', 'public');
            $fleetImage = new FleetImage();
            $fleetImage->fleet_id = $fleet->id;
            $fleetImage->image = $imagePath;
            $fleetImage->save();
        }
    }

    return redirect()->route('fleet.index')->with('success', 'Vehicle updated successfully!');
}

    public function destroy($id)
    {
        $fleet = Fleet::with('images')->findOrFail($id);

        if (! Auth::user()->hasRole('Admin') && $fleet->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        foreach ($fleet->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $fleet->delete();

        return response()->json(['message' => 'Vehicle deleted successfully.']);
    }

    private function normalizeLicensePlate(?string $licensePlate): string
    {
        return strtoupper(preg_replace('/\s+/', '', $licensePlate ?? ''));
    }
}
