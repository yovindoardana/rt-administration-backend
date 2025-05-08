<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_number',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the histories for the house.
     */
    public function histories()
    {
        return $this->hasMany(ResidentHouseHistory::class);
    }
}
