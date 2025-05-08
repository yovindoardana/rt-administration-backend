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

    /**
     * Get the histories for the resident.
     */
    public function histories()
    {
        return $this->hasMany(ResidentHouseHistory::class);
    }

    /**
     * Get the payments for the resident.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
