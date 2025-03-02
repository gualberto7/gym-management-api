<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member_name' => $this->member->name,
            'member_phone' => $this->member->phone,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_by' => $this->created_by,
        ];
    }
}
