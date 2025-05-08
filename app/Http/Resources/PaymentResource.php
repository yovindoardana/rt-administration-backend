<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'resident'         => [
                'id'        => $this->resident->id,
                'full_name' => $this->resident->full_name,
            ],
            'house'            => [
                'id'           => $this->house->id,
                'house_number' => $this->house->house_number,
            ],
            'fee_type'         => $this->fee_type,
            'month'            => $this->month,
            'year'             => $this->year,
            'duration_months'  => $this->duration_months,
            'amount'           => $this->amount,
            'status'           => $this->status,
            'payment_date'     => $this->payment_date->toDateString(),
            'created_at'       => $this->created_at->toDateTimeString(),
            'updated_at'       => $this->updated_at->toDateTimeString(),
        ];
    }
}
