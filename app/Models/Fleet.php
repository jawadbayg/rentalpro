<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;
    protected $table = 'fleet';
    protected $fillable = [
        'user_id',
        'vehicle_no',
        'vehicle_name',
        'vehicle_owner_name',
        'registration_date',
        'vehicle_type',
        'license_plate',
        'manufacturing_year',
        'status',
        'mileage',
        'fuel_type',
        'rental_status',
        'images',
        'no_of_seats',
        'no_of_doors',
        'no_of_bags',
        'color',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);  
    }
    // Fleet.php
    public function images()
    {
        return $this->hasMany(FleetImage::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'fleet_id');
    }


}
