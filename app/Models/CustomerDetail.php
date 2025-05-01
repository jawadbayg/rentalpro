<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    use HasFactory;

    protected $table = 'customer_detail';

    protected $fillable = [
        'user_id',
        'user_validation_id',
        'address',
    ];

    // Optional: Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
