<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'id_card',
        'residency_status',
        'phone_number',
        'marital_status',
    ];

    protected $casts = [
        'residency_status' => 'string',
        'marital_status' => 'string',
    ];

    // public function histories()
    // {
    //     return $this->hasMany(ResidentHouseHistory::class);
    // }

    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
