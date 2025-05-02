<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fleet;
use App\Models\FleetImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Correct import

class FleetController extends Controller
{
    public function index()
    {
        
        $isAdmin = Auth::user()->hasRole('Admin');
        if ($isAdmin) {
            $fleets = Fleet::all(); 
        } else {
            $fleets = Fleet::where('user_id', Auth::user()->id)->get(); // User can see only their own fleets
        }
        return view('fleet.index',compact('fleets','isAdmin'));
    }

    public function show($id)
    {
        return view('fleet.show', compact('id'));
    }

    public function create()
    {
        return view('fleet.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'vehicle_no' => ['required', 'regex:/^[0-9]{4,}$/', 'unique:fleet,vehicle_no'],
                'vehicle_name' => ['required', 'regex:/^[a-zA-Z0-9\s\-]+$/', 'max:255'],
                'vehicle_owner_name' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'registration_date' => ['required', 'date'],
                'vehicle_type' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'license_plate' => ['required', 'regex:/^[A-Z0-9\-]{4,}$/', 'max:255', 'unique:fleet,license_plate'],
                'manufacturing_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
                'status' => ['required', 'in:active,inactive,under_maintenance'],
                'mileage' => ['nullable', 'integer'],
                'fuel_type' => ['nullable', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'images' => ['nullable', 'array'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                'charges_per_day' => ['required']
            ], [
                'vehicle_no.required' => 'Vehicle number is required.',
                'vehicle_no.regex' => 'Vehicle number must be numeric and at least 4 digits.',
                'vehicle_no.unique' => 'This vehicle number is already in use.',

                'vehicle_name.required' => 'Vehicle name is required.',
                'vehicle_name.regex' => 'Vehicle name must contain only letters, numbers, spaces, or dashes.',

                'vehicle_owner_name.required' => 'Owner name is required.',
                'vehicle_owner_name.regex' => 'Owner name must contain only letters and spaces.',

                'license_plate.required' => 'License plate is required.',
                'license_plate.regex' => 'License plate format is invalid.',
                'license_plate.unique' => 'This license plate is already in use.',

                'vehicle_type.required' => 'Vehicle type is required.',
                'vehicle_type.regex' => 'Vehicle type must contain only letters and spaces.',

                'manufacturing_year.required' => 'Manufacturing year is required.',
                'manufacturing_year.min' => 'Manufacturing year cannot be before 1900.',
                'manufacturing_year.max' => 'Manufacturing year cannot be in the future.',

                'charges_per_day.required' => 'Charges per day is required', 

                'status.required' => 'Vehicle status is required.',
                'status.in' => 'Invalid status selected.',

                'images.*.image' => 'Each file must be an image.',
                'images.*.mimes' => 'Images must be jpeg, png, jpg, gif, or svg.',
                'images.*.max' => 'Each image must be less than 2MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $fleet = new Fleet();
        $fleet->user_id = $request->user_id;
        $fleet->vehicle_no = $request->vehicle_no;
        $fleet->vehicle_name = $request->vehicle_name;
        $fleet->vehicle_owner_name = $request->vehicle_owner_name;
        $fleet->registration_date = $request->registration_date;
        $fleet->vehicle_type = $request->vehicle_type;
        $fleet->license_plate = $request->license_plate;
        $fleet->manufacturing_year = $request->manufacturing_year;
        $fleet->status = $request->status;
        $fleet->mileage = $request->mileage;
        $fleet->fuel_type = $request->fuel_type;
        $fleet->price_per_day = $request->charges_per_day;
        $fleet->rental_status = 'Available';
        // $fleet->no_of_seats = $request->no_of_seats;
        // $fleet->no_of_doors = $request->no_of_doors;
        // $fleet->no_of_bags = $request->no_of_bags;
        // $fleet->color = $request->color;
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
    try {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vehicle_no' => [
                'required',
                'integer',
                Rule::unique('fleet')->ignore($id),  
            ],
            'vehicle_name' => 'required|string|max:255',
            'vehicle_owner_name' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fleet')->ignore($id), 
            ],
            'manufacturing_year' => 'required|integer|min:1900|max:' . date('Y'),
            'status' => 'required|in:active,inactive,under_maintenance',
            'mileage' => 'nullable|integer',
            'fuel_type' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        dd($e->errors());
    }

    $fleet = Fleet::findOrFail($id);
    $fleet->user_id = $request->user_id;  // This will update the user_id
    $fleet->vehicle_no = $request->vehicle_no;
    $fleet->vehicle_name = $request->vehicle_name;
    $fleet->vehicle_owner_name = $request->vehicle_owner_name;
    $fleet->registration_date = $request->registration_date;
    $fleet->vehicle_type = $request->vehicle_type;
    $fleet->license_plate = $request->license_plate;
    $fleet->manufacturing_year = $request->manufacturing_year;
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
        $fleet = Fleet::findOrFail($id);
        $fleet->delete();

        return response()->json(['message' => 'Fleet deleted successfully.']);
    }


    
}
