<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Allow mass assignment for these attributes
    protected $fillable = [
        'fp_id',
        'fleet_id',
        'customer_id',
        'from_date',
        'to_date',
        'payment_status',
        'total_price',
        'booking_no',
    ];

    // Cast dates to Carbon instances
    protected $dates = [
        'from_date',
        'to_date',
    ];

    /**
     * Relationship to the customer who made the booking.
     */
    // public function customer()
    // {
    //     return $this->belongsTo(User::class, 'customer_id');
    // }

    // /**
    //  * Relationship to the fleet provider (owner of the vehicle).
    //  */
    // public function fleetProvider()
    // {
    //     return $this->belongsTo(User::class, 'fp_id');
    // }

    // /**
    //  * Relationship to the booked fleet/vehicle.
    //  */
    public function fleet()
    {
        return $this->belongsTo(Fleet::class, 'fleet_id');
    }
    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function fp() {
        return $this->belongsTo(User::class, 'fp_id');
    }
    
}
