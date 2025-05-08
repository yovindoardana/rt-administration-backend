<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentHouseHistoryResource extends JsonResource
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
            'house'            => [
                'id'           => $this->house->id,
                'house_number' => $this->house->house_number
            ],
            'resident'        => [
                'id'          => $this->resident->id,
                'full_name'   => $this->resident->full_name
            ],
            'start_date'      => $this->start_date->toDateString(),
            'end_date'        => $this->end_date?->toDateString(),
            'is_current'      => (bool) $this->is_current,
            'created_at'      => $this->created_at->toDateTimeString(),
            'updated_at'      => $this->updated_at->toDateTimeString(),
        ];
    }
}
