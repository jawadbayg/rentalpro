<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_no',
        'fp_id',
        'fleet_id',
        'customer_id',
        'payment_status',
        'due_date',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function fp()
    {
        return $this->belongsTo(User::class, 'fp_id');
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}
