<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValidation extends Model
{
    use HasFactory;

    protected $table = 'user_validation';

    protected $fillable = [
        'user_id',
        'identity_number',
        'license_number',
        'license_provider',
        'age',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
