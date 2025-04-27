<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetImage extends Model
{
    use HasFactory;

    protected $fillable = ['fleet_id', 'image'];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }
}
