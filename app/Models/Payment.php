<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'house_id',
        'fee_type',
        'month',
        'year',
        'duration_months',
        'amount',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'month'            => 'integer',
        'year'             => 'integer',
        'duration_months'  => 'integer',
        'amount'           => 'integer',
        'payment_date'     => 'date',
    ];

    /**
     * Summary of resident
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resident, Payment>
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Summary of house
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<House, Payment>
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
