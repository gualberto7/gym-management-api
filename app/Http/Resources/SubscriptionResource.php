<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->getStatus(),
            'member' => [
                'id' => $this->member->id,
                'name' => $this->member->name,
                'email' => $this->member->email,
                'phone' => $this->member->phone,
            ],
            'membership' => [
                'id' => $this->membership->id,
                'name' => $this->membership->name,
            ],
        ];
    }
}
