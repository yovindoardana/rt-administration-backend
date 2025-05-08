<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentResource extends JsonResource
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
            'full_name'        => $this->full_name,
            'id_card'          => $this->id_card ? asset('storage/' . ltrim($this->id_card, '/')) : null,
            'residency_status' => $this->residency_status,
            'phone_number'     => $this->phone_number,
            'marital_status'   => $this->marital_status,
            'created_at'       => $this->created_at->toDateTimeString(),
            'updated_at'       => $this->updated_at->toDateTimeString(),
        ];
    }
}
