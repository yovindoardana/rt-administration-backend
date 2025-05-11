<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
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
            'house_number'     => $this->house_number,
            'status'           => $this->status,
            'is_occupied'      => $this->histories->where('is_current', true)->isNotEmpty(),
            'current_resident' => optional($this->histories->firstWhere('is_current', true))->resident,
            'created_at'       => $this->created_at->toDateTimeString(),
            'updated_at'       => $this->updated_at->toDateTimeString(),
        ];
    }
}
