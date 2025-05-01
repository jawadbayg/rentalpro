<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpDetail extends Model
{
    use HasFactory;

    protected $table = 'fp_detail';

    protected $fillable = [
        'user_id',
        'address',
    ];

    // Optional: Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
